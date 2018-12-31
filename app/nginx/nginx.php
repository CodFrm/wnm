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
        Route::get('/nginx', 'Controller@home');
        $group->namespace('App\\nginx');
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