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

    protected $quote_route = null;

    public function __construct($name, & $quote_route = []) {
        $this->name = $name;
        if (func_num_args() === 1) {
            $this->quote_route = &static::$route;
        } else {
            $this->quote_route = &$quote_route;
        }
    }

    public function middleware(string $middleware) {
        $this->quote_route [$this->name]['param']['middleware'][] = $middleware;
        return $this;
    }

    public function namespace(string $namespace) {
        $this->quote_route [$this->name]['param']['namespace'] = $namespace;
        return $this;
    }

    public function addRoute(array $methods, string $url, $controller) {
        foreach ($methods as $value) {
            $value = strtoupper($value);
            if (in_array($value, static::$method)) {
                $this->quote_route [$this->name]['method'][$value][$url]['controller'] = $controller;
            }
        }
        return $this;
    }
}