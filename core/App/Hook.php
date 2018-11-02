<?php
/**
 *============================
 * author:Farmer
 * time:18-11-1 上午11:52
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace WNPanel\Core\App;


class Hook {

    protected static $hook = [];


    public static function listen(string $name, $func) {
        static::$hook[$name][] = $func;
    }

    /**
     * 添加一个hook
     * @param string $name
     * @param mixed ...$param
     */
    public static function add(string $name, $callback, ...$param) {
        foreach (static::$hook[$name] as $func) {
            $ret = call_user_func_array($func, $param);
            if (is_null($callback) && call_user_func($callback, $ret) === false) {
                return false;
            }
        }
        return true;
    }

}