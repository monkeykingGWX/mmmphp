<?php

/**
 * 核心类
 */

namespace mmmphp\lib;

class App
{

    /**
     * 加载控制器
     */
    public static function run ()
    {
        $route      = new \mmmphp\lib\Route();
        $module     = strtolower($route->module);
        $controller = ucfirst($route->controller);
        $action     = strtolower($route->action);

        $file = APP_PATH . '/' .  $module . '/'. Conf::get('CONTROLLER_NAME') .'/' . $controller . '.php';

        if (is_file($file)) {
            $ctrl = '\\' .APP_NAME  . '\\' .  $module . '\\' . Conf::get('CONTROLLER_NAME') . '\\' . $controller;
            $obj = new $ctrl($module, $controller, $action);

            if (!method_exists($obj, $action)) {
                throwErr ( $module . '/' . $controller . '/' . $action . '方法不存在', function (){
                    http_response_code(404);
                });
            }

            $act = strtolower($route->action);
            $obj->$act();    // 执行控制器方法
        } else {
            throwErr ($module . '/' . $controller . '控制器不存在', function (){
                http_response_code(404);
            });
        }
    }

    /**
     * 自动加载类
     * @param $path string  如：new \mmmphp\lib\Route()
     */
    public static function load ($path)
    {
        $tmpArr = explode('\\', $path);
        $class  = ucfirst(array_pop($tmpArr));
        $file   = MMMPHP_PATH . '/../' . implode('/', $tmpArr) . '/' . $class . '.php';

        if (is_file($file)) {
            include_once $file;
        }
    }

}