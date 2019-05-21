<?php
/**
 * 入口文件
 */

// 定义常量
define('APP_PATH', './app');    // 定义应用目录
define('APP_NAME', 'app');    // 定义应用目录名
define('APP_CONF', './app/conf');    // 定义应用配置文件目录
define('APP_DEBUG', true);    // 调试模式，开发环境下建议为true

require 'vendor/autoload.php';
require './mmmphp/mmmphp.php';