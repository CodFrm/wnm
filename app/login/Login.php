<?php
/**
 *============================
 * author:Farmer
 * time:18-11-1 下午4:14
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace App\login;


use WNPanel\Core\App\Hook;

class Login {

    public function view($path) {
        ob_start();
        require $path . '.php';
        return ob_get_clean();
    }

    public function login(\swoole_http_request $_request, \swoole_http_response $_response) {
        if (strtoupper($_request->server['request_method']) === 'GET') {
            return $this->view("view/login");
        } else {

            $error_num = read_config('MB_LOGIN_ERROE_NUM' . $_request->server['remote_addr'], 1800);
            $error_num = $error_num ?: 0;
            if ($error_num >= 5 && read_config('MB_LOGIN_TIME' . $_request->server['remote_addr'], 1800)) {
                return '登录失败超过5次,请半小时后再试';
            }
            write_config('MB_LOGIN_TIME' . $_request->server['remote_addr'], time());
            write_config('MB_LOGIN_ERROE_NUM' . $_request->server['remote_addr'], $error_num + 1);
            if (isset($_request->post['user']) && isset($_request->post['passwd'])) {
                $user = read_config('MB_USER');
                $passwd = read_config('MB_PASSWD');
                if ($user === $_request->post['user'] &&
                    $passwd === $this->encodePasswd($user, $_request->post['passwd'])) {
                    write_config('MB_TOKEN', $token = str_rand(32));
                    $_response->cookie('MB_TOKEN', $token);
                    if (($ret = Hook::add(MB_LOGIN, function ($ret) {
                            if ($ret !== true) {
                                return false;
                            }
                            return true;
                        }, $_request, $_response)) !== true) {
                        return $ret;
                    };
                    return '登录成功';
                } else {
                    return '账号或者密码错误';
                }
            } else {
                return '账号或密码不能为空';
            }
        }
    }

    protected function encodePasswd($u, $p) {
        return hash('sha256', $u . $p . 'MB_PWD');
    }

}

