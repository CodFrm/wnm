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


use WNPanel\Core\App\Hook;
use WNPanel\Core\App\LoadInterface;
use WNPanel\Core\Facade\Route;

class panel implements LoadInterface {

    public function init() {
        // TODO: Implement init() method.
        //HOOK登录成功，跳转到我们的面板
        Hook::listen(MB_LOGIN, function (\swoole_http_request $_request, \swoole_http_response $_response) {
            $_response->status(302);
            $_response->header('location','/');
            return '登录成功，进入面板';
        });
    }

    public function route() {
        // TODO: Implement route() method.
    }
}