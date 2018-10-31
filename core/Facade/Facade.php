<?php
/**
 *============================
 * author:Farmer
 * time:18-10-31 下午3:35
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace WNPanel\Core\Facade;


abstract class Facade {
    abstract public static function getAbstract();

    /**
     * 获取对象实例
     * @return mixed
     * @throws \HuanL\Container\InstantiationException
     */
    protected static function getInstance() {
        return app(static::getAbstract());
    }

    public static function __callStatic($name, $arguments) {
        // TODO: Implement __callStatic() method.
        $instance = static::getInstance();
        if (!$instance) {
            throw new \Exception('A facade root has not been set.');
        }
        return $instance->$name(...$arguments);
    }
}