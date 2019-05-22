<?php

return array(
    // session配置项


    // 时区
    'TIMEZONE'           => 'PRC',

    // 默认模块控制器动作
    'DEFAULT_MODULE'     => 'home',
    'DEFAULT_CONTROLLER' => 'index',
    'DEFAULT_ACTION'     => 'index',

    // 默认文件夹名
    'CONTROLLER_NAME'    => 'controller',    // 控制器文件夹名
    'VIEW_NAME'          => 'view',    // 视图文件夹名
    'MODEL_NAME'         => 'model',    // 模型文件夹名

    'VIEW_EXT' => '.php',    // 模板文件后缀名

    'ERR_FILE'             => MMMPHP_PATH . '/tpl/error.tpl',    // 错误页

    // 日志配置项
    'LOG_FILESIZE'         => 2097152,    // 大小限制
    'LOG_TYPE'             => 'file',    // 日志驱动方式
    'LOG_TIME_FORMAT'      => 'c',    // 时间format

    // 上传配置项
    'UPLOAD_ALLOW_TYPE'    => ['gif', 'jpg', 'png', 'jpeg'],    // 允许文件上传后缀类型
    'UPLOAD_MAX_SIZE'      => 10485760,    // 文件最大上传大小 10M
    'UPLOAD_ROOT_PATH'     => './upload',    // 文件上传根目录
    'UPLOAD_DEFAULT_FIELD' => 'file',    // 默认文件上传域名称
);