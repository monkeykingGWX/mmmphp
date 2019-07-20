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
            'line'    => $err->getLine(),
            'file'    => $err->getFile()
        ];

        // 记录错误日志
        $log = "ERR:message:{$error['message']} on file:{$error['file']}({$error['line']})" . PHP_EOL . self::getTrace();
        Log::record($log);

        self::dealError($error);
    }

    /**
     * 一般错误处理
     */
    public static function errorHandler ($errNo, $errStr, $errFile, $errLine)
    {
        $error = [
            'message' => $errStr,
            'file'    => $errFile,
            'line'    => $errLine
        ];

        // 记录错误日志
        $log = "ERR:message:{$error['message']} on file:{$error['file']}({$error['line']})" . PHP_EOL . self::getTrace();
        Log::record($log);

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
            $log = "ERR:message:{$error['message']} on file:{$error['file']}({$error['line']})" . PHP_EOL . self::getTrace();
            Log::record($log);

            self::dealError($error);
        }

        // 生成日志文件
        Log::save();
    }

    /**
     * 处理错误
     */
    private static function dealError ($err)
    {
        if (APP_DEBUG) {    // 显示错误
            exit ('<b>'.$err['message'].'</b>'.PHP_EOL.'FILE: '.$err['file'].'('.$err['line'].')' . PHP_EOL . nl2br(self::getTrace()));
        } else {    // 跳转错误页
            include Conf::get('ERR_FILE');
        }

        exit;
    }

    private static function getTrace ()
    {
        $log = '';
        $trace = debug_backtrace();
        foreach ($trace as $i => $t)
        {
            if (!isset($t['file']))
            {
                $t['file'] = 'unknown';
            }
            if (!isset($t['line']))
            {
                $t['line'] = 0;
            }
            if (!isset($t['function']))
            {
                $t['function'] = 'unknown';
            }
            $log .= "#$i {$t['file']}({$t['line']}): ";
            if (isset($t['object']) and is_object($t['object']))
            {
                $log .= get_class($t['object']) . '->';
            }
            $log .= "{$t['function']}()\n";
        }

        return $log;
    }
}