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
        foreach (static::$hook[$name] ?? [] as $func) {
            $ret = call_user_func_array($func, $param);
            if (!is_null($callback) && call_user_func($callback, $ret) === false) {
                return $ret;
            }
        }
        return true;
    }

    /**
     * 设置嵌入内容
     * @param string $name
     * @param $value
     */
    public static function embed(string $name, $value, int $priority = 0) {
        static::$hook[$name][] = [$value, $priority];
    }

    /**
     * 设置嵌入点
     * @param string $name
     * @param $callback
     * @param mixed ...$param
     */
    public static function embed_point(string $name, $callback, ...$param) {
        $retList = [];
        foreach (static::$hook[$name] ?? [] as $item) {
            if ($item instanceof \Closure) {
                $retList[$item[1]][] = call_user_func_array($item[0], $param);
            } else {
                $retList[$item[1]][] = $item[0];
            }
        }
        $ret = '';
        foreach ($retList as $items) {
            foreach ($items as $item) {
                $ret .= call_user_func_array($callback, [$item]);
            }
        }
        return $ret;
    }
}