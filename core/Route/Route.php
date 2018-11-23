<?php
/**
 *============================
 * author:Farmer
 * time:18-10-30 下午2:57
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace WNPanel\Core\Route;

use HuanL\Viewdeal\View;

class Route {

    /**
     * 请求方式列表
     * @var array
     */
    public static $method = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

    protected static $groupName = 'default';

    /**
     * @var Group
     */
    protected static $group = null;

    public static $route = [];

    public function __construct() {
        if (is_null(static::$group)) {
            static::$group = new Group(static::$groupName, static::$route);
        }
    }

    public function group($func = null) {
        if (empty($func)) {
            return new Group(static::$groupName);
        }
        if (is_string($func)) {
            static::$groupName = $func;
            static::$group = new Group($func, static::$group->quote_route);
            return static::$group;
        }
        static $n = 0;
        $n++;
        $tmp_group = static::$group;
        $tmp_groupName = static::$groupName;

        static::$groupName = 'group_' . $n;
        $group = new Group(static::$groupName, static::$group->quote_route[$tmp_groupName]['group']);

        static::$group = $group;
        call_user_func($func, $group);
        static::$groupName = $tmp_groupName;
        static::$group = $tmp_group;

        return $group;
    }

    public function get(string $url, $controller) {
        return $this->addRoute(['GET'], $url, $controller);
    }

    public function post(string $url, $controller) {
        return $this->addRoute(['POST'], $url, $controller);
    }

    public function any(string $url, $controller) {
        return $this->addRoute(static::$method, $url, $controller);
    }

    protected function addRoute(array $methods, string $url, $controller) {
        static::$group->addRoute($methods, $url, $controller);
        return $this;
    }

    public static function resolve(\swoole_http_request $request, \swoole_http_response $response) {
        $method = strtoupper($request->server['request_method']);
        foreach (static::$route as $value) {
            $param = $value['param'] ?? [];
            if (($param = static::recursive_resolve($value, $method, $request->server['path_info'], $param)) !== false) {
                static::deal_controller($request, $response, $param);
                return true;
            }
        }
        $response->status(404);
        return false;
    }

    private static function deal_controller(\swoole_http_request $request, \swoole_http_response $response, $param) {
        //加载中间件和处理命名空间
        $ret = null;
        if (isset($param['group_param']['middleware'])) {
            foreach ($param['group_param']['middleware'] as $value) {
                /** @var Middleware $group */
                $middleware = new $value;
                if (($ret = $middleware->handle($request, $response)) !== true) {
                    break;
                }
            }
        }
        if (is_null($ret) || $ret === true) {
            $controller = $param['param']['controller'];
            if (!($controller instanceof \Closure)){
                if (isset($param['group_param']['namespace'])) {
                    $controller = $param['group_param']['namespace'] . '\\' . $controller;
                }
            }
//            $param['uri_param']['_request'] = $request;
//            $param['uri_param']['_response'] = $response;
            app()->instance('route_uri',$param['uri']);
            app()->instance(\Swoole\Http\Request::class, $request);
            app()->instance(\Swoole\Http\Response::class, $response);
            $ret = app()->call($controller, $param['uri_param']);
        }
        if (is_string($ret)) {
            $response->header("content-type", "text/html;charset=utf8");
            $response->write($ret);
        } else if ($ret instanceof View) {
            $response->header("content-type", "text/html;charset=utf8");
            $response->write($ret->execute());
        } else if (method_exists($ret, '__toString')) {
            $response->write($ret);
        }
    }

    private static function merge_param($param1, $param2) {
        if (isset($param2['namespace'])) {
            if (isset($param1['namespace'])) {
                $param1['namespace'] .= '\\' . $param2['namespace'];
            } else {
                $param1['namespace'] = $param2['namespace'];
            }
        }
        if (isset($param2['middleware'])) {
            if (isset($param1['middleware'])) {
                array_merge($param1['middleware'], $param2['middleware']);
            } else {
                $param1['middleware'] = $param2['middleware'];
            }
        }
        return $param1;
    }

    private static function recursive_resolve(array $route, string $method, string $pathinfo, array &$param = []) {
        $param = static::merge_param($param, $route['param'] ?? []);
        foreach ($route['method'][$method] ?? [] as $key => $value) {
            if (($uri_param = static::resolve_uri($pathinfo, $key)) !== false) {
                return [
                    'uri' => $key,
                    'param' => $route['method'][$method][$key],
                    'uri_param' => $uri_param,
                    'group_param' => $param
                ];
            }
        }
        if (empty($route['group'])) {
            return false;
        }
        foreach ($route['group'] as $value) {
            if ($ret = static::recursive_resolve($value, $method, $pathinfo, $param)) {
                return $ret;
            }
        }
        return false;
    }

    private static function resolve_uri($pathinfo, $uri) {
        $uri = substr($uri, 0, 1) === '/' ? $uri : ('/' . $uri);
        $uri_param = [];
        //匹配参数,转换为正则表达式
        $regex = preg_replace_callback([
            '|{(\w+?)}|',
            '|{(\w+?)\?}|'
        ], function ($match) use (&$uri_param) {
            $uri_param[] = $match[1];
            if (substr($match[0], strlen($match[0]) - 2, 1) == '?') {
                return '(\w*)';
            }
            return '(\w+)';
        }, $uri);
        $regex = '^' . $regex . '[/]?$';
        if (!preg_match("|$regex|", $pathinfo, $matches)) {
            return false;
        }
        if (sizeof($matches) - 1 !== sizeof($uri_param)) {
            return false;
        }
        for ($i = 1; $i < sizeof($matches); $i++) {
            $uri_param[$uri_param[$i - 1]] = $matches[$i];
            unset($uri_param[$i - 1]);
        }
        return $uri_param;
    }

}