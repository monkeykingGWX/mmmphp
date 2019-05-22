<?php
/**
 * 错误异常类
 * 开发环境下：只要是错误全部显示出，记录日志
 * 生产环境下：只要是错误全部跳转错误页面，记录日志
 */
namespace mmmphp\lib;

class Error
{

    /**
     * 捕获异常及致命错误
     */
    public static function exception ( $err)
    {
        $error = [
            'message' => $err->getMessage(),
            'line' => $err->getLine(),
            'file' => $err->getFile()
        ];

        // 记录错误日志
        $log = "ERR:message:{$error['message']} on file:{$error['file']}({$error['line']})";
        Log::write($log);

        self::dealError($error);
    }

    /**
     * 一般错误处理
     */
    public static function errorHandler ($errNo, $errStr, $errFile, $errLine)
    {
        $error = [
            'message' => $errStr,
            'file' => $errFile,
            'line' => $errLine
        ];

        // 记录错误日志
        $log = "ERR:message:{$error['message']} on file:{$error['file']}({$error['line']})";
        Log::write($log);

        self::dealError($error);
    }

    /**
     * 致命错误处理
     */
    public static function fatalError ()
    {
        $error = error_get_last();

        if ($error) {
            // 记录错误日志
            $log = "ERR:message:{$error['message']} on file:{$error['file']}({$error['line']})";
            Log::write($log);

            self::dealError($error);
        }
    }

    /**
     * 处理错误
     */
    private static function dealError ($err)
    {
        if (APP_DEBUG) {    // 显示错误
            exit ('<b>'.$err['message'].'</b>'.PHP_EOL.'FILE: '.$err['file'].'('.$err['line'].')');
        } else {    // 跳转错误页
            include Conf::get('ERR_FILE');
        }

        exit;
    }
}