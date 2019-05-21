<?php
/**
 * mmmphp入口文件
 */
// 开启session
session_start();

// 定义常量
define('MMMPHP_PATH', __DIR__ );
define('MMMPHP_LIB_PATH', MMMPHP_PATH . '/lib');

// 调试模式是否开启
if (APP_DEBUG) {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
    ini_set('display_errors', 'On');
} else {
    ini_set('display_errors', 'Off');
}

// 引入函数库文件
include MMMPHP_PATH . '/common/func.php';

// 引入核心类文件
include MMMPHP_LIB_PATH . '/App.php';

// 注册自动加载
spl_autoload_register('\mmmphp\lib\App::load');

// 默认时区 TODO 应从配置文件中取
date_default_timezone_set('PRC');

// 运行框架
\mmmphp\lib\App::run();