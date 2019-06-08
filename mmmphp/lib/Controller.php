<?php
/**
 * 控制器基类
 */
namespace mmmphp\lib;

class Controller
{

    /**
     * 存储模板变量
     * @var array
     */
    private $assign = [];

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
        $path = APP_PATH . '/'. __MODULE__ . '/' . Conf::get('VIEW_NAME') . '/' . strtolower(__CONTROLLER__) . '/';

        if (empty($file)) {
            $filepath = $path . __ACTION__ . Conf::get('VIEW_EXT');
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