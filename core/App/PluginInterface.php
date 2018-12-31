<?php
/**
 *============================
 * author:Farmer
 * time:18-10-31 下午3:33
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace WNPanel\Core\App;


use WNPanel\Core\Route\Group;

interface PluginInterface {

    /**
     * 安装插件
     * @return mixed
     */
    public static function install();

    /**
     * 卸载插件
     * @return mixed
     */
    public static function uninstall();

    /**
     * 开启插件
     * @return mixed
     */
    public static function enable();

    /**
     * 关闭插件
     * @return mixed
     */
    public static function disable();

    /**
     * 插件初始化
     * @return mixed
     */
    public function init();

    /**
     * 插件路由群组
     * @param Group $group
     * @return mixed
     */
    public function route(Group $group);
}