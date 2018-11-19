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
use WNPanel\Core\App\LoadInterface;
use WNPanel\Core\Db\sqlite;
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
            $dir = $this->path2dir($reflex->getFileName());
            inotify_add_watch($notify_id, $dir, IN_CREATE | IN_DELETE | IN_MODIFY);
        }
        $this->setting['inotify_last_time'] = time();
        swoole_event_add($notify_id, function () use ($notify_id) {
            $events = inotify_read($notify_id);
            if (!empty($events) && $this->setting['inotify_last_time'] <= time() - 2) {
                wnm_log('notify file change reload');
                $this->setting['inotify_last_time'] = time();
                $this->server->reload();
            }
        });
    }

    private function path2dir($path) {
        return substr($path, 0, strrpos($path, '/'));
    }

    public function run() {
        $this->startServer();
    }

    protected function loadBaseComponent() {
        $this->singleton('route', Route::class);
    }

    protected function loadDatabase() {
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

    protected function startServer() {
        $this->server = new \swoole_http_server("0.0.0.0", 8000);
        $this->server->on('workerstart', function (\swoole_server $server, $id) {
            wnm_log('worker start' . $id);
            $this->initBasePlugin();
            $server->db = new sqlite($this->rootPath . '/storage/wnm.db');
            app()->instance('db', $server->db);
        });
        $this->server->set([
            'document_root' => $this->rootPath . '/public',
            'enable_static_handler' => true,
        ]);
        $this->server->on('request', function (\swoole_http_request $request, \swoole_http_response $response) {
            //TODO: deal route
            Route::resolve($request, $response);
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
            /** @var LoadInterface $instance */
            $instance = new $value();
            if ($instance instanceof LoadInterface) {
                $instance->init();
                \WNPanel\Core\Facade\Route::group(function () use ($instance) {
                    $instance->route();
                });
            } else {
                throw new \Exception($value . ' is error');
            }
        }
    }

}

