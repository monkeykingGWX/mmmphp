<?php

/**
 * 路由类
 */

namespace mmmphp\lib;

class Route
{
    public $module = '';
    public $controller = '';
    public $action = '';

    public function __construct()
    {
        $this->module     = Conf::get('default_module');
        $this->controller = Conf::get('default_controller');
        $this->action     = Conf::get('default_action');
        $path             = $_SERVER['REQUEST_URI'];

        // 获取module、controller及action参数
        if ($path != '/') {
            $pathArr = explode('/', trim($path, '/'));

            if (isset($pathArr[0])) {
                $this->module = $pathArr[0];
                unset($pathArr[0]);
            }

            if (isset($pathArr[1])) {
                $this->controller = $pathArr[1];
                unset($pathArr[1]);
            }

            if (isset($pathArr[2])) {
                $this->action = $pathArr[2];
                unset($pathArr[2]);
            }
        }

        // 获取get参数
        if (!empty($pathArr)) {
            for ($i = 3, $len = count($pathArr) + 3; $i < $len; $i +=2 ) {
                if (isset($pathArr[$i + 1])) {
                    $_GET[$pathArr[$i]] = $pathArr[$i + 1];
                }
            }
        }
    }
}