<?php
/**
 *============================
 * author:Farmer
 * time:18-11-21 上午11:53
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace WNPanel\Core\App;


abstract class Controller {

    protected static $classPath = [];

    protected function view($template) {
        $view = new \HuanL\Viewdeal\View($this->getClassPath() . '/' . $template . '.html', $this);
        if (app('debug') !== true) {
            $view->setCacheDir(app('root_path') . '/storage/cache/view');
        }
        return $view;
    }

    protected function getClassPath() {
        $className = static::class;
        if (!isset(static::$classPath[$className])) {
            $ref = new \ReflectionClass($this);
            static::$classPath[$className] = path2dir($ref->getFileName());
        }
        return static::$classPath[$className];
    }
}