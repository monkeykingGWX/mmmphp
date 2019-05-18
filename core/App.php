<?php

/**
 * 核心类
 */

namespace core;

class App
{
    public static function run ()
    {
        // 测试自动加载类
        $route = new \core\lib\Route();
        var_dump($route);
        var_dump($_GET);
    }

    /**
     * 自动加载类
     * @param $path string  如：new \core\lib\Route()
     */
    public static function load ($path)
    {
        $tmpArr = explode('\\', $path);
        $class = ucfirst(array_pop($tmpArr));
        $file = ROOT . '/' . implode('/', $tmpArr) . '/' . $class . '.php';

        if (is_file($file)) {
            include_once $file;
        }
    }
}