<?php

namespace mmmphp\lib;

class Log
{
    // 日志级别 从上到下，由低到高
    const EMERG     = 'EMERG';  // 严重错误: 导致系统崩溃无法使用
    const ALERT     = 'ALERT';  // 警戒性错误: 必须被立即修改的错误
    const CRIT      = 'CRIT';  // 临界值错误: 超过临界值的错误，例如一天24小时，而输入的是25小时这样
    const ERR       = 'ERR';  // 一般错误: 一般性错误
    const WARN      = 'WARN';  // 警告性错误: 需要发出警告的错误
    const NOTICE    = 'NOTIC';  // 通知: 程序可以运行但是还不够完美的错误
    const INFO      = 'INFO';  // 信息: 程序输出信息
    const DEBUG     = 'DEBUG';  // 调试: 调试信息
    const SQL       = 'SQL';  // SQL：SQL语句 注意只在调试模式开启时有效

    /**
     * 日志信息
     * @var array
     */
    private static $logs = [];

    /**
     * 日志驱动方式,默认为文件记录
     * @var null
     */
    private static $drive = null;

    /**
     * 初始化日志
     * @param string $drive 日志驱动方式,默认为文件
     * @param array $config 配置项
     */
    public static function init ($config = [], $drive = 'file')
    {
        $class = '\\' . 'mmmphp\lib\log\drive\\' . ucfirst(strtolower($drive));
        self::$drive = new $class($config);
    }

    /**
     * 记录日志信息
     * @param string $msg
     * @param string $level
     * @return void
     */
    public static function record ($msg, $level = self::ERR)
    {
        if ($msg) {
            self::$logs[] = "$level:$msg" . PHP_EOL;
        }
    }

    /**
     * 将日志信息保存
     * @param string $destination 日志记录位置
     * @return void
     */
    public static function save ($destination = '')
    {
        if (empty(self::$logs)) {
            return;
        }

        // 记录日志
        self::$drive->write(implode('', self::$logs), $destination);
        // 清空日志
        self::$logs = [];
    }

    /**
     * 直接将信息保存
     * @param string $msg 信息
     * @param string destination 日志保存位置
     */
    public static function write ($msg, $destination = '')
    {
        self::$drive->write($msg, $destination);
    }
}