<?php
/**
 * 控制器基类
 */
namespace mmmphp\lib;

class Controller
{
    public $module = '';
    public $controller = '';
    public $action = '';

    /**
     * 存储模板变量
     * @var array
     */
    private $assign = [];

    public function __construct($module, $controller, $action)
    {
        $this->module     = $module;
        $this->controller = $controller;
        $this->action     = $action;

        // 初始化日志系统
        $logConf = [
            'log_time_format' => Conf::get('LOG_TIME_FORMAT'),
            'log_filesize'    => Conf::get('LOG_FILESIZE'),
            'log_path'        => APP_PATH . '/logs/'.$module
        ];
        Log::init($logConf, Conf::get('LOG_TYPE'));
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
        $path = APP_PATH . '/'. $this->module . '/' . Conf::get('VIEW_NAME') . '/' . strtolower($this->controller) . '/';

        if (empty($file)) {
            $filepath = $path . $this->action . Conf::get('VIEW_EXT');
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

    public function __destruct()
    {
        // 生成日志文件
        Log::save();
    }
}