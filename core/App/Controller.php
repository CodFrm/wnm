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


class Controller {

    protected static $classPath = '';

    protected function view($template) {
        $view = new \HuanL\Viewdeal\View($this->getClassPath() . '/' . $template . '.html', $this);
        if (app('debug') !== true) {
            $view->setCacheDir(app('root_path') . '/storage/view');
        }
        return $view;
    }

    protected function getClassPath() {
        if (empty(static::$classPath)) {
            $ref = new \ReflectionClass($this);
            static::$classPath = path2dir($ref->getFileName());
        }
        return static::$classPath;
    }
}