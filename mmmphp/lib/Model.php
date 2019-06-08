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

    // 可进行连贯操作的方法名
    private $callMethod = [
        'field'  => '*',
        'where'  => '',
        'group'  => '',
        'having' => '',
        'order'  => '',
        'limit'  => ''
    ];

    public function __construct($tableName = '', $tablePrefix = '', $connection = [])
    {
        if ($tableName) {
            $this->tableName = $tableName;
        } elseif (empty($this->tableName)) {
            $className = strtolower(get_class($this));
            $this->tableName = (basename(str_replace('\\', '/', $className)));
        }

        if ($tablePrefix) {
            $this->tablePrefix = $tablePrefix;
        } elseif (empty($this->tablePrefix)) {
            $this->tablePrefix = Conf::get('db_prefix', 'database');
        }

        if ($connection) {
            $this->db = Db::getInstance($connection);
        } else {
            $connection = Conf::get('', 'database');
            $config = [
                'host'    => $connection['DB_HOST'],
                'user'    => $connection['DB_USER'],
                'pass'    => $connection['DB_PASS'],
                'charset' => $connection['DB_CHARSET'],
                'db'      => $connection['DB_NAME'],
                'port'    => $connection['DB_PORT']
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
     * @param bool $security 是否启动事务
     * @return bool
     */
    public function executes (array $sqls, bool $security = false)
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
     * @param string $table
     * @return array|bool|null
     */
    public function select ($resultType = MYSQLI_ASSOC)
    {
        $table = $this->tableName;
        $this->setParam();
        $sql = "SELECT {$this->callMethod['field']} FROM {$table} 
            {$this->callMethod['where']} 
            {$this->callMethod['group']}
            {$this->callMethod['having']} 
            {$this->callMethod['order']} 
            {$this->callMethod['limit']}";
        $this->initParam();

        return $this->db->query($sql, $resultType);
    }

    /**
     * @param string $table
     * @return bool|mixed
     */
    public function find($resultType = MYSQLI_ASSOC)
    {
        $ret = $this->select($resultType);

        if ($ret) {
            return $ret[0];
        } else {
            return false;
        }
    }

    public function count ($resultType = MYSQLI_NUM) {
        $this->field('count(*)');
        $ret = $this->select($resultType);

        if ($ret !== false) {
            return $ret[0][0];
        } else {
            return false;
        }
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

    public function save (array $data, array $where)
    {
        return $this->db->save($data, $where, $this->tablePrefix . $this->tableName);
    }

    public function delete (array $where)
    {
        return $this->db->delete($where, $this->tablePrefix . $this->tableName);
    }

    /**
     * 连贯操作
     * @param $methodName
     * @param $arguments
     * @return $this
     */
    public function __call($methodName, $arguments)
    {
        $methodName = strtolower($methodName);

        if (array_key_exists($methodName, $this->callMethod) && isset($arguments[0])) {
            $this->callMethod[$methodName] = $arguments[0];
        }

        return $this;
    }

    private function setParam ()
    {
        if ($this->callMethod['where'] && stripos($this->callMethod['where'],'WHERE') === false) {
            $this->callMethod['where'] = 'WHERE '. $this->callMethod['where'];
        }

        if ($this->callMethod['group'] && stripos($this->callMethod['group'],'GROUP') === false) {
            $this->callMethod['group'] = 'GROUP BY  '. $this->callMethod['group'];
        }

        if ($this->callMethod['having'] && stripos($this->callMethod['having'],'HAVING') === false) {
            $this->callMethod['having'] = 'HAVING '. $this->callMethod['having'];
        }

        if ($this->callMethod['order'] && stripos($this->callMethod['order'],'ORDER BY') === false) {
            $this->callMethod['order'] = 'ORDER BY  '. $this->callMethod['order'];
        }

        if ($this->callMethod['limit'] && stripos($this->callMethod['limit'],'LIMIT') === false) {
            $this->callMethod['limit'] = 'LIMIT '. $this->callMethod['limit'];
        }
    }

    private function initParam ()
    {
        foreach ($this->callMethod as $k => $v) {
            $this->callMethod[$k] = '';
        }

        $this->callMethod['field'] = '*';
    }
}