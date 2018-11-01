<?php
/**
 *============================
 * author:Farmer
 * time:18-10-31 下午3:54
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace App\login;


use WNPanel\Core\Route\Middleware;

class LoginAuth implements Middleware {

    public function handle(\swoole_http_request $request, \swoole_http_response $response) {
        // TODO: Implement handle() method.
        return true;
    }
}