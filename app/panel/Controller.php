<?php
/**
 *============================
 * author:Farmer
 * time:18-11-19 下午9:42
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace App\panel;


class Controller {

    public function home() {
        ob_start();
        require 'view/home.php';
        return ob_get_clean();
    }

    public function wsocket(\swoole_http_response $response) {
        $response->status(101);
    }
}