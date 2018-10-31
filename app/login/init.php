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

class init implements LoadInterface {

    public function init() {
        Route::get('/', function () {
            return '登录页面';
        });
    }
}