<?php
/**
 *============================
 * author:Farmer
 * time:18-10-31 下午3:36
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace WNPanel\Core\Facade;


/**
 * @method static \WNPanel\Core\Route\Route get(string $url, $controller)
 * @method static \WNPanel\Core\Route\Route post(string $url, $controller)
 * @method static \WNPanel\Core\Route\Route any(string $url, $controller)
 * @method static \WNPanel\Core\Route\Route group(string $url, $controller)
 * Class Route
 * @package WNPanel\Core\Facade
 */
class Route extends Facade {
    /**
     * 获取抽象类型
     * @return string
     */
    public static function getAbstract() {
        return 'route';
    }
}