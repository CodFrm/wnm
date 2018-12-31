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

define('MB_HTML_HEAD', 'mb_html_head');
define('MB_LEFT_MENU', 'mb_left_menu');
define('MB_PLUGIN', 'mb_plugin');
define('MB_SETTING', 'mb_setting');

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
        Route::get('/', 'Controller@home');
        Route::get('/wsocket', 'Controller@wsocket');
        $group->namespace('App\\panel');
    }

    /**
     * active demo 暂时没有想去实现
     * @param array $param
     * @param string $html
     * @return string|string[]|null
     * @throws \HuanL\Container\InstantiationException
     */
    public function isurl($param = [], $html = '') {
        $html = $html[0] ?? '';
        $req = app(\Swoole\Http\Request::class);
        $info = $req->server['path_info'];
        if (substr($param['href'], 0, strlen($info)) == $info) {
            $html = preg_replace('#class="(.*?)"#', 'class="$1 active"', $html);
        }
        return $html;
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