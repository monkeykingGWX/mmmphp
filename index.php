<?php
/**
 * 入口文件
 */

// 开启session
session_start();

// 定义常量
define('ROOT', __DIR__);
define('CORE', ROOT . '/core');
define('DEBUG', true);

// 调试模式是否开启
require 'vendor/autoload.php';

if (DEBUG) {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
    ini_set('display_errors', 'On');
} else {
    ini_set('display_errors', 'Off');
}

// 引入函数库文件
include CORE . '/common/func.php';

// 引入核心类文件
include CORE . '/App.php';

// 注册自动加载
spl_autoload_register('\core\App::load');

// 默认时区 TODO 应从配置文件中取
date_default_timezone_set('PRC');

// 运行框架
\core\App::run();