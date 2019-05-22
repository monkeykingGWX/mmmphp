<?php

/**
 * 配置加载类
 */

namespace mmmphp\lib;

class Conf
{
    /**
     * 存储已加载的配置项
     * @var array
     */
    public static $conf = [];

    /**
     * 获取配置文件配置项，若$name不存在则表示取整个配置文件的配置项
     * @param string $name  配置项名
     * @param string $file 配置文件名
     * @return bool|mixed
     */
    public static function get ($name, $file = 'conf')
    {
        $name = strtoupper($name);
        // 避免重复加载配置
        if ($name) {
            if (isset(self::$conf[$file][$name])) {
                return self::$conf[$file][$name];
            }
        } else {
            if (isset(self::$conf[$file])) {
                return self::$conf[$file];
            }
        }

        $path = MMMPHP_PATH . '/conf/' . $file . '.php';    // 框架配置目录
        $appPath = APP_CONF . '/' . $file . '.php';    // 自定义配置目录

        if (is_file($path) || is_file($appPath)) {
            if (is_file($path)) {
                self::$conf[$file] = include_once $path;
            }

            if (is_file($appPath)) {
                if (isset(self::$conf[$file]) && is_array(self::$conf[$file])) {
                    self::$conf[$file] = array_merge(self::$conf[$file], include_once $appPath);
                } else {
                    self::$conf[$file] = include_once $appPath;
                }
            }

            if ($name) {
                return self::$conf[$file][$name] ?? false;
            } else {
                return self::$conf[$file];
            }

        } else {
            return false;
        }
    }
}