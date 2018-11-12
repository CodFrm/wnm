<?php
/**
 *============================
 * author:Farmer
 * time:18-10-31 下午3:09
 * blog:blog.icodef.com
 * function:需要加载的核心插件
 *============================
 */

//这也是有加载顺序的,最好让权限验证的插件在最前边

return [
    \App\login\init::class,
    \App\nginx\nginx::class,
    \App\panel\panel::class
];