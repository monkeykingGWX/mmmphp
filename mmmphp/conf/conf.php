<?php

return array(
    // session配置项


    // 时区
    'TIMEZONE'           => 'PRC',

    // 默认模块控制器动作
    'DEFAULT_MODULE'     => 'home',
    'DEFAULT_CONTROLLER' => 'index',
    'DEFAULT_ACTION'     => 'index',

    // 普通模式下模块控制器动作参数名
    'NORMAL_MODULE_PARAM' => 'm',
    'NORMAL_CONTROLLER_PARAM' => 'c',
    'NORMAL_ACTION_PARAM' => 'a',

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

    // 分页配置项
    'PAGE_PARAM' => 'mmmphp_page',    // 分页参数名
    'PAGE_LIST_ROW' => 25,    // 每页显示条数
    'PAGE_LIST_NUM' => 9,    // 每页显示分页链接数
);