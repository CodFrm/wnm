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

if (!function_exists('read_config')) {
    function read_config(string $key, $expire = 0) {
        $result = \WNPanel\Core\Facade\Db::table('config')->get([
            'key' => $key
        ]);
        if ($result === false) {
            return false;
        }
        if ($expire && $result['time'] + $expire < time()) {
            return false;
        }
        return $result['value'];
    }
}

if (!function_exists('write_config')) {
    function write_config(string $key, string $value) {
        if (read_config($key) !== false) {
            return \WNPanel\Core\Facade\Db::table('config')->where(['key' => $key])->update([
                'value' => $value,
                'time' => time()
            ]);
        } else {
            return \WNPanel\Core\Facade\Db::table('config')->insert([
                'key' => $key,
                'value' => $value,
                'time' => time()
            ]);
        }
    }
}

if (!function_exists('expire_config')) {
    function expire_config(string $key) {
        return \WNPanel\Core\Facade\Db::table('config')->where(['key' => $key])->update([
            'time' => time()
        ]);
    }
}

if (!function_exists('env')) {
    function env($key) {
        static $env_array = [];
        if (empty($env_array)) {
            $tmp = fopen(app('root_path') . '/.env', 'r+');
            if ($tmp) {
                while ($str = fgets($tmp)) {
                    $pos = strpos($str, '=');
                    $value = trim(substr($str, $pos + 1));
                    if ($value === 'true') {
                        $value = true;
                    } else if ($value === 'false') {
                        $value = false;
                    }
                    $env_array[substr($str, 0, $pos)] = $value;
                }
            }
        }
        return $env_array[$key] ?? '';
    }
}

if (!function_exists('wnm_log')) {
    function wnm_log($content) {
        echo '[' . date('Y/m/d H:i:s') . ']' . $content . "\n";
    }
}

if (!function_exists('path2dir')) {
    function path2dir($path) {
        return substr($path, 0, strrpos($path, '/'));
    }
}

if (!function_exists('view_embed')) {
    function view_embed(string $name, $format = null, ...$param) {
        return \WNPanel\Core\App\Hook::embed_point($name, function ($array) use ($format, $param) {
            if (empty($format)) {
                return $array;
            }
            if (is_string($format)) {
                return preg_replace_callback('/\[(.*?)\]/', function ($match) use ($array) {
                    return $array[$match[1]] ?? '';
                }, $format);
            } else if (is_callable($format)) {
                return preg_replace_callback('/\[(.*?)\]/', function ($match) use ($array) {
                    return $array[$match[1]] ?? '';
                }, call_user_func_array($format, [$array, $param]));
            }
        });
    }
}