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

class Route {

    /**
     * 请求方式列表
     * @var array
     */
    public static $method = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

    protected static $groupName = 'default';

    protected static $route = [];

    public function group(\Closure $func) {
        static $n = 0;
        $n++;
        $tmp_groupName = static::$groupName;
        static::$groupName = 'group_' . $n;
        $tmp_route =& static::$route;
        static::$route = &static::$route[$tmp_groupName]['group'];
        $group = new Group(static::$groupName, $tmp_groupName);
        call_user_func($func, $group);
        static::$groupName = $tmp_groupName;
        static::$route = &$tmp_route;
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
        foreach ($methods as $value) {
            $value = strtoupper($value);
            if (in_array($value, static::$method)) {
                static::$route[static::$groupName]['method'][$value][$url]['controller'] = $controller;
            }
        }
        return $this;
    }

    public static function resolve(\swoole_http_request $request, \swoole_http_response $response) {
        $method = strtoupper($request->server['request_method']);
        $param = static::$route['default']['param'] ?? [];
        if (($param = static::recursive_resolve(static::$route['default'], $method, $request->server['path_info'], $param)) !== false) {
            $ret = app()->call($param['param']['controller'], $param['uri_param']);
            if (is_string($ret)) {
                $response->header("content-type","text/html;charset=utf8");
                $response->write($ret);
            }
        } else {
            $response->status(404);
        }
    }

    private static function merge_param($param1, $param2) {
        if (isset($param2['namespace'])) {
            if (isset($param1['namespace'])) {
                $param1['namespace'] .= $param2['namespace'];
            } else {
                $param1['namespace'] = $param2['namespace'];
            }
        }
        if (isset($param2['middleware'])) {
            if (isset($param1['middleware'])) {
                array_push($param1['middleware'], $param2['middleware']);
            } else {
                $param1['middleware'] = $param2['middleware'];
            }
        }
        return $param1;
    }

    private static function recursive_resolve(array $route, string $method, string $pathinfo, array &$param = []) {
        if (!isset($route['method'][$method])) {
            return false;
        }
        $param = static::merge_param($param, $route['param'] ?? []);
        foreach ($route['method'][$method] as $key => $value) {
            if (($uri_param = static::resolve_uri($pathinfo, $key)) !== false) {
                return [
                    'uri' => $key,
                    'param' => $route['method'][$method][$key],
                    'uri_param' => $uri_param
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
        if (substr($pathinfo, strlen($pathinfo) - 1) !== '/') {
            $pathinfo .= '/';
        }
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
        $regex = '^' . $regex . '$';
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