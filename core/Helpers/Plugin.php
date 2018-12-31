<?php
/**
 *============================
 * author:Farmer
 * time:18-12-31 下午11:04
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace WNPanel\Core\Helpers;


use WNPanel\Core\App\PluginInterface;
use WNPanel\Core\Facade\Db;

class Plugin extends Helper {

    /**
     * 插件来源,0 核心插件 1 本地插件 2 composer 3 网络
     */
    const SOURCE_CORE = 0;
    const SOURCE_LOCAL = 1;
    const SOURCE_COMPOSER = 2;
    const SOURCE_WEB = 3;

    public static function getInfo($package) {
        return Db::table('plugin')->get([
            'package' => $package
        ]);
    }

    /**
     * 插件是否安装
     * @param $package
     * @return array|bool
     */
    public static function isinstall($package) {
        $result = self::getInfo($package);
        if ($result && $result['is_install']) {
            return $result;
        }
        return false;
    }

    /**
     * 插件是否开启
     * @param $package
     * @return bool|array
     */
    public static function isenable($package) {
        $result = self::isinstall($package);
        if ($result && $result['is_enable'] == 1) {
            return $result;
        }
        return false;
    }

    public static function install($package, $source = 0) {
        if (self::isinstall($package)) {
            return -1;
        }
        switch ($source) {
            case self::SOURCE_CORE:
                {
                    if (self::getInfo($package)) {
                        Db::table('plugin')->update(['is_install' => 1, 'is_enable' => 1]);
                    } else {
                        Db::table('plugin')->insert([
                            'package' => $package, 'name' => $package,
                            'path' => $package, 'source' => $source, 'is_install' => 1,
                            'is_enable' => 1
                        ]);
                    }
                    /** @var PluginInterface $package */
                    $package::install();
                    $package::enable();//核心插件直接开启
                    break;
                }
        }
    }

    public static function enable($package) {
        if (self::enable($package)) {
            return -1;
        }
        $info = self::getInfo($package);
        switch ($info['source']) {
            case self::SOURCE_CORE:
                {
                    break;
                }
        }
    }

    public static function disable() {

    }


}