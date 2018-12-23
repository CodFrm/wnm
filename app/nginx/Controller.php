<?php
/**
 *============================
 * author:Farmer
 * time:18-12-23 下午10:54
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace App\nginx;

use WNPanel\Core\App\Controller as BaseController;

class Controller extends BaseController {

    public function home() {
        return $this->view('view/home');
    }
}