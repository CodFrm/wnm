<?php
/**
 *============================
 * author:Farmer
 * time:18-11-2 上午10:23
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace WNPanel\Core\Facade;

use WNPanel\Core\Component\Db\sqlite;

/**
 * @method static sqlite table(string $table)
 * Class Route
 * @package WNPanel\Core\Facade
 */
class Db extends Facade {
    /**
     * 获取抽象类型
     * @return string
     */
    public static function getAbstract() {
        return 'db';
    }
}