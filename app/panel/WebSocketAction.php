<?php
/**
 *============================
 * author:Farmer
 * time:18-12-30 下午8:03
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace App\panel;


use WNPanel\Core\App\WSActionInterface;

class WebSocketAction implements WSActionInterface {

    public static function action(\swoole_websocket_server $server, \swoole_websocket_frame $frame) {
        // TODO: Implement action() method.
        print_r('emm');
    }
}