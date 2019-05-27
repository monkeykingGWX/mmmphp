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
        $controller = self::parseStr($route->controller);
        $action     = $route->action;

        // 初始化日志系统
        $logConf = [
            'log_time_format' => Conf::get('LOG_TIME_FORMAT'),
            'log_filesize'    => Conf::get('LOG_FILESIZE'),
            'log_path'        => APP_PATH . '/logs/'.$module
        ];
        Log::init($logConf, Conf::get('LOG_TYPE'));

        $file = APP_PATH . '/' .  $module . '/'. Conf::get('CONTROLLER_NAME') .'/' . $controller . '.php';

        if (is_file($file)) {
            $ctrl = '\\' .APP_NAME  . '\\' .  $module . '\\' . Conf::get('CONTROLLER_NAME') . '\\' . $controller;
            $obj = new $ctrl();

            if (!method_exists($obj, $action)) {
                throwErr ( $module . '/' . $controller . '/' . $action . '方法不存在', function (){
                    http_response_code(404);
                });
            }

            // 将模块/控制器/方法存放入常量中
            define('__MODULE__', $module);
            define('__CONTROLLER__', $controller);
            define('__ACTION__', strtolower($action));

            $obj->$action();    // 执行控制器方法
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

    /**
     * 将user_del 转变为UserDel或userDel返回
     * @param $str
     * @return string
     */
    private static function parseStr ($str)
    {
        $tmpArr = explode('_', $str);
        $arr    = array_map(function ($val)
        {
            return ucfirst($val);
        }, $tmpArr);

        return implode("", $arr);
    }
}