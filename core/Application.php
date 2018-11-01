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

    public function __construct(string $rootPath) {
        parent::__construct();
        $this->rootPath = $rootPath;
        $this->loadBaseComponent();
        $this->loadDatabase();
        $this->initBasePlugin();
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
        $http = new \swoole_http_server("0.0.0.0", 8000);
        $http->on('workerstart', function (\swoole_server $server, $id) {
            $server->db = new sqlite($this->rootPath . '/storage/wnm.db');
            app()->instance('db', $server->db);
        });
        $http->set([
            'document_root' => $this->rootPath . '/public',
            'enable_static_handler' => true,
        ]);
        $http->on('request', function (\swoole_http_request $request, \swoole_http_response $response) {
            //TODO: deal route
            Route::resolve($request, $response);
            $response->end();
        });
        $http->start();
    }

    protected function initBasePlugin() {
        $list = require $this->rootPath . '/config/plugin.php';
        foreach ($list as $value) {
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

