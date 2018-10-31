<?php
/**
 *============================
 * author:Farmer
 * time:18-10-30 下午2:11
 * blog:blog.icodef.com
 * function:
 *============================
 */

namespace WNPanel\Core\Db;

use SQLite3;

class sqlite {

    /**
     * @var SQLite3
     */
    protected $db = null;

    /**
     * sqlite constructor.
     * @param string $path
     * @param \Closure|null $create
     * @throws \Exception
     */
    public function __construct(string $path, \Closure $create = null) {
        try {
            $this->db = new SQLite3($path, SQLITE3_OPEN_READWRITE);
        } catch (\Exception $exception) {
            if (strpos($exception->getMessage(), 'unable to open database file')) {
                $this->db = new SQLite3($path);
                call_user_func($create, $this);
            } else {
                throw $exception;
            }
        }
    }

    public function exec(string $sql) {
        return $this->db->exec($sql);
    }


}