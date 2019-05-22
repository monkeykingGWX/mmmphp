<?php
/**
 * 模型基类
 */
namespace mmmphp\lib;

class Model
{
    // db类实例
    protected $db = null;

    // 数据表名
    protected $tableName = '';

    // 表明前缀
    protected $tablePrefix = '';

    // 数据库连接配置信息
    protected $connection = [];

    public function __construct($tableName = '', $tablePrefix = '', $connection = [])
    {
        if ($tableName) {
            $this->tableName = $tableName;
        } else {
            $className = strtolower(get_class($this));
            $this->tableName = (basename(str_replace('\\', '/', $className)));
        }

        if ($tablePrefix) {
            $this->tablePrefix = $tablePrefix;
        } else {
            $this->tablePrefix = Conf::get('db_prefix', 'database');
        }

        if ($connection) {
            $this->db = Db::getInstance($connection);
        } else {
            $connection = Conf::get('', 'database');
            $config = [
                'host' => $connection['DB_HOST'],
                'user' =>  $connection['DB_USER'],
                'pass' =>  $connection['DB_PASS'],
                'charset' =>  $connection['DB_CHARSET'],
                'db' =>  $connection['DB_NAME'],
                'port' =>  $connection['DB_PORT']
            ];
            $this->db = Db::getInstance($config);
        }
    }

    /**
     * 执行sql语句，并返回影响结果
     * @param $sql
     * @return bool|int
     */
    public function execute ($sql)
    {
        return $this->db->execute($sql);
    }

    /**
     * 返回查询结果
     * @param $sql
     * @param int $resultType
     * @return array|bool|null
     */
    public function query ($sql, $resultType = MYSQLI_ASSOC)
    {
        return $this->db->query($sql, $resultType);
    }

    /**
     * 获取自增ID
     * @return int|string
     */
    public function getInsertId ()
    {
        return $this->db->getInsertId();
    }

    /**
     * 执行多条sql语句
     * @param array $sqls
     * @param bool $security
     * @return bool
     */
    public function executes (array $sqls, bool $security)
    {
        return $this->db->executes($sqls, $security);
    }

    /**
     * 开启事务
     */
    public function startTrans () {
        $this->db->startTrans();
    }

    /**
     * 关闭事务
     * @param bool $flag 为true，提交，为false回滚
     */
    public function endTrans ($flag = false)
    {
        $this->db->endTrans($flag);
    }

    /**
     * 防sql注入
     * @param $str
     * @return array|mixed|string
     */
    public function filter ($str)
    {
         return $this->db->filter($str);
    }

    public function getError ()
    {
        return $this->db->getError();
    }

    /**
     * @return array|bool|null
     */
    public function select ()
    {
        return $this->db->select($this->tablePrefix . $this->tableName);
    }

    /**
     * @return bool|mixed
     */
    public function find ()
    {
        return $this->db->find($this->tablePrefix . $this->tableName);
    }

    /**
     * @param array $data
     * @return bool|int
     */
    public function add (array $data)
    {
        return $this->db->add($data, $this->tablePrefix . $this->tableName);
    }

    /**
     * @param array $datas
     * @return bool|int
     */
    public function adds (array $datas)
    {
        return $this->db->adds($datas, $this->tablePrefix . $this->tableName);
    }

    public function save (array $data, string $where)
    {
        return $this->db->save($data, $where, $this->tablePrefix . $this->tableName);
    }

    public function delete (string $where)
    {
        return $this->db->delete($where, $this->tablePrefix . $this->tableName);
    }
}