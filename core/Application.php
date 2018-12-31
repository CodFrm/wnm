<?php
/**
 *============================
 * author:Farmer
 * time:18-10-30 下午2:28
 * blog:blog.icodef.com
 * function:面板应用启动页面
 *============================
 */


namespace WNPanel\Core;


use HuanL\Container\Container;
use HuanL\SSH2\ssh;
use HuanL\Viewdeal\View;
use WNPanel\Core\App\PluginInterface;
use WNPanel\Core\App\WSActionInterface;
use WNPanel\Core\Db\sqlite;
use WNPanel\Core\Helpers\Plugin;
use WNPanel\Core\Helpers\System;
use WNPanel\Core\Route\Route;

require 'functions.php';

class Application extends Container {

    protected $rootPath = '';

    protected $setting = [];

    protected $pluginList = [];

    /**
     * @var \swoole_server
     */
    protected $server = null;

    public function __construct(string $rootPath) {
        parent::__construct();
        $this->rootPath = $rootPath;
        $this->initialization();
        $this->loadBaseComponent();
        $this->loadDatabase();
//        $this->initBasePlugin();
    }

    public function initialization() {
        $this->instance('root_path', $this->rootPath);
        $this->setting = require $this->rootPath . '/config/setting.php';
        $this->pluginList = require $this->rootPath . '/config/plugin.php';

        $this->instance('debug', $this->setting['debug']);

    }

    protected function enableDebug() {
        if (extension_loaded('inotify')) {
            $this->watchfile_hotrestart();
        }
    }

    protected function watchfile_hotrestart() {
        $notify_id = inotify_init();
        if (!$notify_id) {
            return false;
        }
        foreach ($this->pluginList as $item) {
            //TODO:目录遍历
            try {
                $reflex = new \ReflectionClass($item);
            } catch (\ReflectionException $e) {
                wnm_log('plugin:' . $item . ' ' . $e->getMessage());
                return false;
            }
            $dir = path2dir($reflex->getFileName());
            inotify_add_watch($notify_id, $dir, IN_CREATE | IN_DELETE | IN_MODIFY);
        }
        $this->setting['inotify_last_time'] = time();
        swoole_event_add($notify_id, function () use ($notify_id) {
            $events = inotify_read($notify_id);
            if (!empty($events) && $this->setting['inotify_last_time'] <= time() - 2) {
                if ($events[0]['mask'] != IN_DELETE) {
                    return;
                }
                //TODO: 暂时先用IN_DELETE判断最后修改
                wnm_log('notify file change reload');
                $this->setting['inotify_last_time'] = time();
                $this->server->reload();
            }
        });
    }

    public function run() {
        $this->startServer();
    }

    protected function loadBaseComponent() {
        $this->singleton('route', Route::class);
        $this->initSSH();
    }

    protected function initSSH() {
        $ssh = new ssh('localhost', System::ssh_port());
        if (!$ssh->connect()) {
            throw new \Exception('ssh connect error,please open the ssh service');
        }
        //TODO:暂时直接用root权限,后面感觉需要用其他操作改一下
        if (!$ssh->login_pubkey('root', $this->rootPath . '/storage/secret/rsa.pub', $this->rootPath . '/storage/secret/rsa.pri')) {
            throw new \Exception('ssh secret key error');
        }
        $this->instance(ssh::class, $ssh);
        $this->alias('ssh', ssh::class);
    }

    protected function loadDatabase() {
        //TODO:数据库需要一些密码等权限操作
        $db = new sqlite($this->rootPath . '/storage/wnm.db', function (sqlite $db) {
            //TODO: Create Table
            $dbcontent = explode(';', file_get_contents($this->rootPath . "/storage/db.sql"));
            foreach ($dbcontent as $sql) {
                try {
                    $db->exec($sql);
                } catch (\Throwable $exception) {

                }
            }
        });
        app()->instance('db', $db);
    }

    protected function bindWebSocket() {
        $shared_table = new \swoole_table(8);
        $shared_table->column('classname', \swoole_table::TYPE_STRING, 64);
        $shared_table->create();
        $this->server->on('open', function (\swoole_websocket_server $server, \swoole_http_request $request) use ($shared_table) {
            $response = new class extends \swoole_http_response {
                public $code = 200;

                public function status($http_code, $reason = NULL) {
                    $this->code = $http_code;
                }
            };
            $ret = Route::resolve($request, $response);
            if ($response->code != 101) {
                $server->disconnect($request->fd, 1000, 'Permission error');
                return false;
            }
            try {
                $reflectionClass = new \ReflectionClass($ret);
                if (in_array(WSActionInterface::class, $reflectionClass->getInterfaceNames())) {
                    $shared_table->set($request->fd, ['classname' => $ret]);
                }
            } catch (\Throwable $exception) {

            }
        });
        $this->server->on('message', function (\swoole_websocket_server $server, \swoole_websocket_frame $frame) use ($shared_table) {
            if ($shared_table->exist($frame->fd)) {
                $class = $shared_table->get($frame->fd, 'classname');
                call_user_func_array([$class, 'action'], [$server, $frame]);
            }
        });
    }

    protected function startServer() {
        $this->initBasePlugin();
        $this->server = new \swoole_websocket_server("0.0.0.0", 8000);
        $this->bindWebSocket();
        $this->server->set([
            'document_root' => $this->rootPath . '/public',
            'enable_static_handler' => true,
            'worker_num' => 4,
            'log_file' => $this->rootPath . '/storage/log/wnm-' . date('Y-m-d') . '.log'
        ]);
        $this->server->on('workerstart', function (\swoole_server $server, $id) {
            wnm_log('worker start id:' . $id);
            $this->instance('worker_id', $id);
            $server->db = new sqlite($this->rootPath . '/storage/wnm.db');
            app()->instance('db', $server->db);
        });
        $this->server->on('request', function (\swoole_http_request $request, \swoole_http_response $response) {
            //TODO: deal route
            $ret = Route::resolve($request, $response);
            if (is_string($ret)) {
                $response->header("content-type", "text/html;charset=utf8");
                $response->write($ret);
            } else if ($ret instanceof View) {
                $response->header("content-type", "text/html;charset=utf8");
                $response->write($ret->execute());
            } else if (method_exists($ret, '__toString')) {
                $response->write($ret);
            }
            $response->end();
        });
        $this->server->on('start', function () {
            if ($this->setting['debug']) {
                $this->enableDebug();
            }
        });

        $this->server->start();
    }

    protected function initBasePlugin() {
        foreach ($this->pluginList as $value) {
            /** @var PluginInterface $instance */
            $instance = new $value();
            if ($instance instanceof PluginInterface) {
                if (!Plugin::isenable($value)) {
                    Plugin::install($value, Plugin::SOURCE_CORE);
                }
                $instance->init();
                \WNPanel\Core\Facade\Route::group(function ($group) use ($instance) {
                    $instance->route($group);
                });
            } else {
                throw new \Exception($value . ' is error');
            }
        }
    }

}

