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

define('MB_HTML_HEAD', 'mb_html_head');
define('MB_LEFT_MENU', 'mb_left_menu');
define('MB_PLUGIN', 'mb_plugin');
define('MB_SETTING','mb_setting');

function view_embed(string $name, string $format = '') {
    return Hook::embed_point($name, function ($array) use ($format) {
        if (empty($format)) {
            return $array;
        }
        return preg_replace_callback('/\[(.*?)\]/', function ($match) use ($array) {
            return $array[$match[1]] ?? '';
        }, $format);
    });
}