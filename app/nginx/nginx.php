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


use WNPanel\Core\App\Hook;
use WNPanel\Core\App\LoadInterface;
use WNPanel\Core\Facade\Route;
use WNPanel\Core\Route\Group;

class nginx implements LoadInterface {

    public function init() {
        // TODO: Implement init() method.
        Hook::embed(MB_LEFT_MENU, [
            'title' => '<i class="iconfont icon-web"></i> nginx',
            'href' => '/nginx'
        ]);
    }

    public function route(Group $group) {
        // TODO: Implement route() method.
        Route::group(function () {
            Route::get('/nginx', 'Controller@home');
        })->namespace('App\\nginx');
    }
}