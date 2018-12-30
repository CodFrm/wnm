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

use WNPanel\Core\App\Controller as BaseController;

class Controller extends BaseController {

    public function home() {
        return $this->view('view/home');
    }

    public function wsocket(\swoole_http_response $response) {
        $response->status(101);
        return WebSocketAction::class;
    }
}