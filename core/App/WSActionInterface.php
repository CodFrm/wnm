<?php
/**
 *============================
 * author:Farmer
 * time:18-12-30 下午7:56
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace WNPanel\Core\App;


interface WSActionInterface {
    public static function action(\swoole_websocket_server $server, \swoole_websocket_frame $frame);
}