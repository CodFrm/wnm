<?php
/**
 *============================
 * author:Farmer
 * time:18-12-31 下午11:27
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace WNPanel\Core\Helpers;


abstract class Helper {

    public static function __callStatic($name, $arguments) {
        // TODO: 利用成员private和debug_backtrace来实现各个插件的权限控制
        // 其实感觉挺难的,不用Helper也有可以自己实现,防君子不防小人
    }
}