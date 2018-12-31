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
    protected static $db = null;

    protected $table = '';

    protected $where = '';

    protected $sql = '';

    protected $bindValues = [];

    /**
     * sqlite constructor.
     * @param string $path
     * @param \Closure|null $create
     * @throws \Exception
     */
    public function __construct(string $path = '', \Closure $create = null) {
        if (func_num_args() >= 1) {
            try {
                static::$db = new SQLite3($path, SQLITE3_OPEN_READWRITE);
            } catch (\Exception $exception) {
                if (strpos($exception->getMessage(), 'unable to open database file')) {
                    static::$db = new SQLite3($path);
                    call_user_func($create, $this);
                } else {
                    throw $exception;
                }
            }
        }
    }

    public function table($table) {
        $this->table = $table;
        $this->where = '';
        $this->bindValues = [];
        return $this;
    }

    public function exec(string $sql) {
        return static::$db->exec($sql);
    }

    /**
     * @param array $where
     * @return bool|array
     */
    public function get(array $where) {
        $this->sql = 'select * from ' . $this->table . ' where ';
        $values = [];
        foreach ($where as $key => $value) {
            $values[] = $value;
            $this->sql .= "$key=? and";
        }
        $this->sql = substr($this->sql, 0, strlen($this->sql) - 3);
        $this->sql .= ' limit 1';
        if ($stmt = $this->prepare($this->sql, $values)) {
            if ($result = $stmt->execute()) {
                return $result->fetchArray();
            }
            return false;
        }
        return false;
    }

    public function insert(array $values) {
        $this->_insert($values, $param);
        if ($stmt = $this->prepare($this->sql, $param)) {
            if ($stmt->execute()->finalize()) {
                return static::$db->changes();
            }
            return false;
        }
        return false;
    }

    protected function _insert(array $values, &$param) {
        $this->sql = 'insert into ' . $this->table . '(`' . implode('`,`', array_keys($values)) . '`) values(';
        foreach ($values as $value) {
            $this->sql .= '?,';
            $param[] = $value;
        }
        $this->sql = substr($this->sql, 0, strlen($this->sql) - 1);
        $this->sql .= ')';
    }

    public function where(array $where) {
        foreach ($where as $key => $value) {
            $this->where .= "and $key=? ";
            $this->bindValues[] = $value;
        }
        return $this;
    }

    public function update(array $set) {
        $data = '';
        foreach ($set as $key => $value) {
            $data .= ",`{$key}`=?";
            $param[] = $value;
        }
        $this->bindValues = array_merge($param, $this->bindValues);
        $data = substr($data, 1);
        $this->where = substr($this->where, 3);
        $this->sql = "update {$this->table} set $data where " . $this->where;
        if ($stmt = $this->prepare($this->sql, $this->bindValues)) {
            if ($stmt->execute()->finalize()) {
                return static::$db->changes();
            }
            return false;
        }
        return false;
    }

    public function delete() {
        $this->where = substr($this->where, 3);
        $this->sql = 'delete from ' . $this->table . ' where ' . $this->where;
        if ($stmt = $this->prepare($this->sql, $this->bindValues)) {
            if ($stmt->execute()->finalize()) {
                return static::$db->changes();
            }
            return false;
        }
        return false;
    }

    public function prepare($sql, $bindValues) {
        if ($stmt = static::$db->prepare($sql)) {
            for ($i = 0; $i < count($bindValues); $i++) {
                $stmt->bindValue($i + 1, $bindValues[$i]);
            }
            return $stmt;
        }
        return false;
    }

}