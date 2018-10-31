<?php
/**
 *============================
 * author:Farmer
 * time:18-10-24 下午4:59
 * blog:blog.icodef.com
 * function:引导
 *============================
 */

require_once 'vendor/autoload.php';

$app = new \WNPanel\Core\Application(realpath(__DIR__));
$app->run();