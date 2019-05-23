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
        $path              = $_SERVER['REQUEST_URI'];

        // 模式检测
        $pathType = self::checkPath();

        if ($pathType == 'normal') {
            if (isset($_GET['m']) && !empty($_GET['m'])) {
                $this->module = $_GET['m'];
            }
            if (isset($_GET['c']) && !empty($_GET['c'])) {
                $this->controller = $_GET['c'];
            }
            if (isset($_GET['a']) && !empty($_GET['a'])) {
                $this->action = $_GET['a'];
            }
        } else {
            if ($path != '/') {
                $pos = strpos($path, '?') ? : strlen($path);
                $url = substr($path, 0, $pos);;
                $pathArr = explode('/', trim($url, '/'));

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
                    if (!isset($_GET[$pathArr[$i]]) && isset($pathArr[$i + 1])) {
                        $_GET[$pathArr[$i]] = $pathArr[$i + 1];
                    }
                }
            }
        }

    }

    /**
     * 模式检验
     * @return string 一般模式:normal 混合模式:mixed 重写模式:rewrite
     */
    public static function checkPath ()
    {
        $str = ltrim($_SERVER['REQUEST_URI'], '/');

        if (!empty($str) && ($str{0} == '?' || substr($str, 0, 10) == 'index.php?')) {
            $type = 'normal';
        } elseif (strpos($str, '?')) {
            $type = 'mixed';
        } else {
            $type = 'rewrite';
        }

        return $type;
    }
}