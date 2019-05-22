<?php

namespace mmmphp\lib\log\drive;

class File
{
    private $config = [
        'log_time_format' => 'c',
        'log_filesize' => 1048576,   // 1M大小
        'log_path' => ''
    ];

    /**
     * 实例化
     * @param array $config 配置
     */
    public function __construct($config = [])
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * 写日志
     * @param string $log 日志内容
     * @param string 日志名
     * @return void
     */
    public function write ($log, $destination = '')
    {
        $now = date($this->config['log_time_format']);

        if (!$destination) {
            $destination = rtrim($this->config['log_path'], '/\\') . '/'.date('Y-m-d') . '.log';
        }

        // 创建日志目录
        if (!is_dir(dirname($destination))) {
            mkdir(dirname($destination), 0755, true);
        }

        // 日志超过配置的大小，则重新生成日志
        if (is_file($destination) && filesize($destination) > $this->config['log_filesize']) {
            rename($destination, dirname($destination).'/'.time() . '-' . basename($destination));
        }

        // 记录日志
        error_log("[{$now}] ".$_SERVER['REMOTE_ADDR'].' '.$_SERVER['REQUEST_URI'].PHP_EOL."{$log}".PHP_EOL, 3, $destination);
    }
}