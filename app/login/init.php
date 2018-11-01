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
        Route::group(function () {
            Route::get('/', 'init@login');
        })->namespace('App\\login');
        Route::group('auth')->middleware(LoginAuth::class);
    }

    public function route() {
        // TODO: Implement route() method.

    }

    public function login() {
        return 'login';
    }
}
