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
 * @method static \WNPanel\Core\Component\Route\Route get(string $url, $controller)
 * @method static \WNPanel\Core\Component\Route\Route post(string $url, $controller)
 * @method static \WNPanel\Core\Component\Route\Route any(string $url, $controller)
 * @method static \WNPanel\Core\Component\Route\Group group(\Closure $func = null)
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