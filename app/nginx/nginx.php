<?php
/**
 *============================
 * author:Farmer
 * time:18-10-31 下午5:04
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace App\nginx;


use WNPanel\Core\App\LoadInterface;
use WNPanel\Core\Facade\Route;

class nginx implements LoadInterface {

    public function init() {
        // TODO: Implement init() method.
    }

    public function route() {
        // TODO: Implement route() method.
        Route::get('/nginx', function () {
            return 'nginx厉害了';
        });
    }
}