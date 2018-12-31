<?php
/**
 *============================
 * author:Farmer
 * time:18-12-31 下午9:51
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace WNPanel\Core\Tests;


use PHPUnit\Framework\TestCase;
use WNPanel\Core\Helpers\System;

class helpSystemTest extends TestCase {

    public function testGetPort() {
        $this->assertNotFalse(System::ssh_port());
    }
}