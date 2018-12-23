<?php
/**
 *============================
 * author:Farmer
 * time:18-10-31 下午3:33
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace WNPanel\Core\App;


use WNPanel\Core\Route\Group;

interface LoadInterface {
    public function init();

    public function route(Group $group);
}