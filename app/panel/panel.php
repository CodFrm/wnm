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

class panel implements LoadInterface {

    public function init() {
        // TODO: Implement init() method.
        Hook::listen(MB_LOGIN, function () {
            return '登录成功，进入面板';
        });
    }

    public function route() {
        // TODO: Implement route() method.
    }
}