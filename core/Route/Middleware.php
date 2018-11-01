<?php
/**
 *============================
 * author:Farmer
 * time:18-10-31 下午4:04
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace WNPanel\Core\Route;


interface Middleware {
    public function handle(\swoole_http_request $request, \swoole_http_response $response);
}