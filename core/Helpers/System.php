<?php
/**
 *============================
 * author:Farmer
 * time:18-12-31 下午8:31
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace WNPanel\Core\Helpers;


class System {

    /**
     * 复制文件
     * @param $source
     * @param $dest
     * @return bool
     */
    public static function copy($source, $dest) {
        return copy($source, $dest);
    }

    /**
     * 移动文件
     * @param $old
     * @param $new
     * @return bool
     */
    public static function move($old, $new) {
        return rename($old, $new);
    }

    /**
     * 删除文件
     * @param $file
     * @return bool
     */
    public static function delete($file) {
        return unlink($file);
    }

    /**
     * 创建目录
     * @param $path
     * @param int $mode
     * @param bool $recursive
     * @return mixed
     */
    public static function mkdir($path, $mode = 0700, $recursive = false) {
        return self::mkdir($path, $mode, $recursive);
    }

}