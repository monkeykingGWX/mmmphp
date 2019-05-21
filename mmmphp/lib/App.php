<?php

/**
 * 核心类
 */

namespace mmmphp\lib;

class App
{
    public static $module = '';
    public static $controller = '';
    public static $action = '';

    /**
     * 存储模板变量
     * @var array
     */
    private $assign = [];

    /**
     * 加载控制器
     */
    public static function run ()
    {
        $route = new \mmmphp\lib\Route();
        self::$module = strtolower($route->module);
        self::$controller = ucfirst($route->controller);
        self::$action = strtolower($route->action);

        $file = APP_PATH . '/' .  self::$module . '/'. Conf::get('CONTROLLER_NAME') .'/' . self::$controller . '.php';

        if (is_file($file)) {
            $ctrl = '\\' .APP_NAME  . '\\' .  self::$module . '\\' . Conf::get('CONTROLLER_NAME') . '\\' . self::$controller;
            $obj = new $ctrl;

            if (!method_exists($obj, self::$action)) {
                throwErr ( self::$module . '/' . self::$controller . '/' . self::$action . '方法不存在', function (){
                    http_response_code(404);
                });
            }

            $act = strtolower($route->action);
            $obj->$act();    // 执行控制器方法
        } else {
            throwErr (self::$module . '/' . self::$controller . '控制器不存在', function (){
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
        $class = ucfirst(array_pop($tmpArr));
        $file = MMMPHP_PATH . '/../' . implode('/', $tmpArr) . '/' . $class . '.php';

        if (is_file($file)) {
            include_once $file;
        }
    }

    /**
     * 注入模板变量
     * @param string $name
     * @param $value
     */
    public function assign (string $name, $value)
    {
        $this->assign[$name] = $value;
    }

    public function display (string $file = '')
    {
        $path = APP_PATH . '/'. self::$module . '/' . Conf::get('VIEW_NAME') . '/' . strtolower(self::$controller) . '/';

        if (empty($file)) {
            $filepath = $path . self::$action . Conf::get('VIEW_EXT');
        } else if ($file{0} == '/') { // 使用绝对路径
            $filepath = $file;
        } else {    // 使用相对路径
            $filepath = $path . $file;
        }

        if (is_file($filepath)) {
            extract($this->assign);
            include $filepath;
        } else {
            throwErr ($filepath . '模板文件不存在', function (){
                http_response_code(404);
            });
        }
    }
}