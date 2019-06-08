<?php
/**
 * 数据库类
 */

namespace mmmphp\lib;

class Db
{
    // 该类实例
    private static $db = null;

    // 配置项
    private $config =  [
        'host'    => 'localhost',
        'user'    => 'root',
        'pass'    => '',
        'charset' => 'utf8',
        'db'      => 'test',
        'port'    => 3306
    ];

    // mysqli实例
    private $mysqli = null;

    /**
     * 数据库初始化
     * @param array $config 配置项
     */
    private function __construct(array $config = [])
    {
        $this->config = array_merge($this->config, $config);

        // 连接数据库
        $this->mysqli = mysqli_connect($this->config['host'], $this->config['user'], $this->config['pass'], $this->config['db'], $this->config['port']);
        if (mysqli_connect_errno()) {
            throw new \Exception(mysqli_connect_error());
        }

        // 设置字符集
        if (!mysqli_set_charset($this->mysqli, $this->config['charset'])) {
            throw new \Exception(mysqli_error($this->mysqli));
        }
    }

    /**
     * 获取本类实例
     * @param array $config 配置项
     * @return Db|null
     */
    public static function getInstance (array $config = [])
    {
        if (self::$db === null) {
            self::$db = new self($config);
        }

        return self::$db;
    }

    /**
     * @param $sql string
     * @return bool|int
     */
    public function execute ($sql)
    {
        if (APP_DEBUG) {
            Log::record($sql, Log::SQL);
        }

        $flag = mysqli_query($this->mysqli, $sql);

        if ($flag === false) {
            return false;
        } else {
            return mysqli_affected_rows($this->mysqli);
        }
    }

    /**
     * @return int|string
     */
    public function getInsertId ()
    {
        return mysqli_insert_id($this->mysqli);
    }

    /**
     * @param $sql
     * @param int $resultType
     * @return array|bool|null
     */
    public function query ($sql, $resultType = MYSQLI_ASSOC)
    {
        if (APP_DEBUG) {
            Log::record($sql, Log::SQL);
        }

        $result = mysqli_query($this->mysqli, $sql);

        if ($result === false) {
            return false;
        }

        $rows = mysqli_fetch_all($result, $resultType);
        mysqli_free_result($result);
        return $rows;
    }

    /**
     * 执行多条sql
     * @param $sqls array
     * @param $security bool 若为true，则启用事务来执行多条sql
     * @return bool
     */
    public function executes (array $sqls, $security = false)
    {
        if ($security) {
            $this->startTrans();
        }

        foreach ($sqls as $sql) {
            $ret = $this->execute($sql);

            if ($ret === false) {
                $security && $this->endTrans(false);
                return false;
            }
        }

        $security && $this->endTrans(true);
        return true;
    }

    /**
     * 开启事务
     */
    public function startTrans ()
    {
        // 关闭自动提交
        mysqli_autocommit($this->mysqli, false);
    }

    /**
     * 关闭事务
     * @param bool $flag
     */
    public function endTrans ($flag = false)
    {
        if (!$flag) {
            mysqli_rollback($this->mysqli);
        } else {
            mysqli_commit($this->mysqli);
        }

        mysqli_autocommit($this->mysqli, true);
    }

    /**
     * 数据过滤，防sql注入
     * @param $str
     * @return array|mixed|string
     */
    public function filter ($str)
    {
        if (is_array($str)) {
            foreach ($str as $k => $v) {
                $str[$k] = self::filter($v);
            }
        } else {
            $str = mysqli_real_escape_string($this->mysqli, $str);
            $str = str_replace("%", "\%", $str );
        }

        return $str;
    }

    /**
     * 获取数据库错误信息
     * @return string
     */
    public function getError ()
    {
        return mysqli_error($this->mysqli) . mysqli_errno($this->mysqli);
    }

    /**
     * @param string $table
     * @param array $data
     * @return bool|int
     */
    public function add (array $data, string $table)
    {
        $data = $this->filter($data);
        $intField = $intVal = '';

        foreach ($data as $k => $v) {
            $intField .= "`$k`,";
            $intVal .= "'$v',";
        }

        $intField = substr($intField, 0, -1);
        $intVal   = substr($intVal, 0, -1);
        $sql      = "INSERT INTO {$table}($intField) VALUES($intVal)";

        return $this->execute($sql);
    }

    /**
     * @param string $table
     * @param array $datas
     * @return bool|int
     */
    public function adds (array $datas, string $table)
    {
        $datas = $this->filter($datas);
        $intField = $intVal = '';

        foreach ($datas[0] as $k => $v) {
            $intField .= "`$k`,";
        }

        foreach ($datas as $v) {
            $intVal .= "(";
            foreach ($v as $key => $val) {
                $intVal .= "'$val',";
            }
            $intVal = substr($intVal, 0, -1);
            $intVal .= "),";
        }

        $intField = substr($intField, 0, -1);
        $intVal   = substr($intVal, 0, -1);
        $sql      = "INSERT INTO {$table}($intField) VALUES{$intVal}";

        return $this->execute($sql);
    }

    /**
     * @param array $data
     * @param string $where
     * @param string $table
     * @return bool|int
     */
    public function save (array $data, array $where, string $table) {
        $data  = $this->filter($data);
        $where = $this->parseWhere($where);

        $tmp = '';
        foreach ($data as $field => $value) {
            $tmp .= "`{$field}`='{$value}',";
        }
        $tmp = trim($tmp, ',');
        $sql = "UPDATE {$table} SET $tmp $where";

        return $this->execute($sql);
    }

    private function parseWhere (array $where) {
        $where = $this->filter($where);

        if (count($where)) {
            $str = 'WHERE ';
            foreach ($where as $k => $v) {
                $str .= "$k='$v' AND ";
            }

            return substr($str, 0, -4);
        } else {
            return '';
        }
    }

    /**
     * @param string $where
     * @param string $table
     * @return bool|int
     */
    public function delete (array $where, string $table)
    {
        $where = $this->parseWhere($where);
        $sql   = "DELETE FROM {$table} {$where}";

        return $this->execute($sql);
    }

    public function __destruct()
    {
        mysqli_close($this->mysqli);
    }
}