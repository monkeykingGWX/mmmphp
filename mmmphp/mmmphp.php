<?php
/**
 * mmmphp入口文件
 */

// 定义常量
define('ROOT_PATH',dirname(__DIR__) );
define('MMMPHP_PATH', __DIR__ );
define('MMMPHP_LIB_PATH', MMMPHP_PATH . '/lib');

// 调试模式是否开启
if (APP_DEBUG) {
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

// 注册错误和异常处理
register_shutdown_function('mmmphp\lib\Error::fatalError');
set_error_handler('\mmmphp\lib\Error::errorHandler');
set_exception_handler('\mmmphp\lib\Error::exception');

// 默认时区
date_default_timezone_set(\mmmphp\lib\Conf::get('TIMEZONE'));

// 运行框架
\mmmphp\lib\App::run();