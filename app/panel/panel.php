<?php
/**
 *============================
 * author:Farmer
 * time:18-11-10 下午10:39
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace App\panel;


use HuanL\Viewdeal\View;
use WNPanel\Core\App\Hook;
use WNPanel\Core\App\LoadInterface;
use WNPanel\Core\Facade\Route;
use WNPanel\Core\Route\Group;

require 'util.php';

class panel implements LoadInterface {

    public function init() {
        // TODO: Implement init() method.
        //HOOK登录成功，跳转到我们的面板
        Hook::listen(MB_LOGIN, function (\swoole_http_request $_request, \swoole_http_response $_response) {
            $_response->status(302);
            $_response->header('location', '/');
            return '登录成功，进入面板';
        });
        View::setLayout(__DIR__ . '/view/layout.html');
    }

    public function route(Group $group) {
        // TODO: Implement route() method.
        Route::group(function () {
            Route::get('/', 'Controller@home');
            Route::get('/wsocket', 'Controller@wsocket');
        })->namespace('App\\panel');
    }


}