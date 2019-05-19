<?php

/**
 * 核心类
 */

namespace core;

class App
{
    /**
     * 加载控制器
     */
    public static function run ()
    {
        // 测试自动加载类
        $route = new \core\lib\Route();
        $module = $route->module;
        $controller = ucfirst($route->controller);
        $action = strtolower($route->action);

        // TODO controller文件夹名应从配置文件取
        $file = ROOT . '/' . $module . '/controller/' . $controller . '.php';

        if (is_file($file)) {
            $ctrl = '\\' . $module . '\controller\\' . $controller;
            $obj = new $ctrl;

            if (!method_exists($obj, $action)) {
                throwErr ($module . '/' . $controller . '/' . $action . '方法不存在', function (){
                    // TODO 404 完善
                    echo '404';
                });
            }

            $obj->$action();    // 执行控制器方法
        } else {
            throwErr ($module . '/' . $controller . '控制器不存在', function (){
                // TODO 404 完善
                echo '404';
            });
        }
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