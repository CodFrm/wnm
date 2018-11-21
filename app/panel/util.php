<?php
/**
 *============================
 * author:Farmer
 * time:18-11-21 下午4:44
 * blog:blog.icodef.com
 * function:
 *============================
 */

use WNPanel\Core\App\Hook;

define('MB_LEFT_MENU', 'mb_left_menu');

function view_embed(string $name, string $format) {
    return Hook::embed_point($name, function ($array) use ($format) {
        return preg_replace_callback('/\[(.*?)\]/', function ($match) use ($array) {
            return $array[$match[1]] ?? '';
        }, $format);
    });
}