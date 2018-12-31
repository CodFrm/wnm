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


use HuanL\SSH2\ssh;

class System extends Helper {

    public static $cache = [];

    /**
     * 复制文件
     * @param $source
     * @param $dest
     * @return bool
     */
    public static function copy($source, $dest) {
        return copy($source, $dest);
    }

    public static function copy_dir($src, $dst) {
        foreach (glob($src . "/*") as $val) {
            $length = strripos($val, "/");
            $shen = substr($val, $length);
            $newDir = $dst . $shen;
            if (is_dir($val)) {
                if (!file_exists($newDir)) {
                    self::mkdir($newDir);
                }
                self::copy_dir($val, $newDir);
            } else {
                copy($val, $newDir);
            }
        }
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
        return mkdir($path, $mode, $recursive);
    }

    /**
     * 获取ssh端口
     * @return bool|int|mixed
     */
    public static function ssh_port() {
        if ($port = self::cacheGet('ssh_port')) {
            return $port;
        }
        $path = '/etc/ssh/sshd_config';
        if (!file_exists($path)) {
            return false;
        }
        $content = file_get_contents($path);
        $port = 22;
        if (preg_match('#Port[\s]+([\w]+)#', $content, $match) > 0) {
            $port = $match[1];
        }
        self::cacheSet('ssh_port', $port);
        return $port;
    }

    public static function exec($command) {
        /** @var ssh $ssh */
        $ssh = app('ssh');
        return $ssh->exec($command);
    }

    protected static function cacheGet($key) {
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }
        return false;
    }

    protected static function cacheSet($key, $val) {
        self::$cache[$key] = $val;
    }

}