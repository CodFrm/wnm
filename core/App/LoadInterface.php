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

interface LoadInterface {

    /**
     * 安装插件
     * @return mixed
     */
    public function install();

    /**
     * 卸载插件
     * @return mixed
     */
    public function uninstall();

    /**
     * 插件初始化
     * @return mixed
     */
    public function init();

    /**
     * 关闭插件
     * @return mixed
     */
    public function close();

    /**
     * 插件路由群组
     * @param Group $group
     * @return mixed
     */
    public function route(Group $group);
}