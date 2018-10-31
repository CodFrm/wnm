<?php
/**
 *============================
 * author:Farmer
 * time:18-10-31 上午10:24
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace WNPanel\Core\Route;


class Group extends Route {

    protected $name = null;

    protected $fatherGroupName = '';

    public function __construct($name) {
        $this->name = $name;
    }

    public function middleware($middleware) {
        static::$route[$this->name]['param']['middleware'] = $middleware;
        return $this;
    }

    public function namespace($namespace) {
        static::$route[$this->name]['param']['namespace'] = $namespace;
        return $this;
    }
}