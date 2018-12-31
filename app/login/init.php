<?php
/**
 *============================
 * author:Farmer
 * time:18-10-31 下午3:16
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace App\login;


use WNPanel\Core\App\LoadInterface;
use WNPanel\Core\Facade\Route;
use WNPanel\Core\Route\Group;

define('MB_LOGIN', 'MB_LOGIN');

class init implements LoadInterface {

    public function init() {
        Route::group(function () {
            Route::any('/login', 'Login@login');
        })->namespace('App\\login');
        Route::group('auth')->middleware(LoginAuth::class);
    }

    public function route(Group $group) {
        // TODO: Implement route() method.

    }

    /**
     * 安装插件
     * @return mixed
     */
    public function install() {
        // TODO: Implement install() method.
    }

    /**
     * 卸载插件
     * @return mixed
     */
    public function uninstall() {
        // TODO: Implement uninstall() method.
    }

    /**
     * 关闭插件
     * @return mixed
     */
    public function close() {
        // TODO: Implement close() method.
    }
}
