<?php
/**
 *============================
 * author:Farmer
 * time:18-10-31 上午10:16
 * blog:blog.icodef.com
 * function:
 *============================
 */

use HuanL\Container\Container;

if (!function_exists('str_rand')) {
    function str_rand($length, $type = 2) {
        $randString = '1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
        $retStr = '';
        $type = 9 + $type * 26;
        for ($n = 0; $n < $length; $n++) {
            $retStr .= substr($randString, mt_rand(0, $type), 1);
        }
        return $retStr;
    }
}

if (!function_exists('app')) {
    /**
     * @param null $abstract
     * @param array $parameter
     * @return Container|mixed
     * @throws \HuanL\Container\InstantiationException
     */
    function app($abstract = null, $parameter = []) {
        if (is_null($abstract)) {
            return Container::getInstance();
        }
        return Container::getInstance()->make($abstract, $parameter);
    }
}