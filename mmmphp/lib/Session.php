<?php

namespace mmmphp\lib;

class Session
{
    /**
     * 存取session
     * @param string $name
     * @param mixed $value
     * @param string $prefix
     * @param int $lifetime
     * @return void
     */
    public static function set (string $name, $value, string $prefix = '')
    {
        $prefix   = $prefix ? : Conf::get('session_prefix');
        $lifetime = Conf::get('session_lefttime');

        if (session_status() !== PHP_SESSION_ACTIVE ) {
            ini_set('session.gc_maxlifetime',   $lifetime);
            ini_set('session.cookie_lifetime',  $lifetime);
            session_start();
        }

        if (strpos($name, '.')) {
            list($name1,$name2) = explode('.', $name);

            if ($prefix) {
                $_SESSION[$prefix][$name1][$name2] = $value;
            } else {
                $_SESSION[$name1][$name2] = $value;
            }
        } else {
            if ($prefix) {
                $_SESSION[$prefix][$name] = $value;
            } else {
                $_SESSION[$name] = $value;
            }
        }
    }

    /**
     * 获取session
     * @param $name
     */
    public static function get ($name, $prefix = '')
    {
        if (session_status() !== PHP_SESSION_ACTIVE ) {
            session_start();
        }
        $prefix   =  $prefix ? : Conf::get('session_prefix');

        if (strpos($name, '.')) {
            list($name1, $name2) = explode('.', $name);

            if ($prefix) {
                $session = $_SESSION[$prefix][$name1][$name2] ?? null;
            } else {
                $session = $_SESSION[$name1][$name2] ?? null;
            }
        } else {
            if ($prefix) {
                $session = $_SESSION[$prefix][$name] ?? null;
            } else {
                $session = $_SESSION[$name] ?? null;
            }
        }

        return $session;
    }

    /**
     * 删除单个session
     * @param $name
     * @param $prefix
     */
    public static function unsetOne ($name, $prefix = '')
    {
        if (session_status() !== PHP_SESSION_ACTIVE ) {
            session_start();
        }
        $prefix   =  $prefix ? : Conf::get('session_prefix');

        if (strpos($name, '.')) {
            list($name1, $name2) = explode('.', $name);

            if ($prefix) {
                unset($_SESSION[$prefix][$name1][$name2]);
            } else {
                unset($_SESSION[$name1][$name2]);
            }
        } else {
            if ($prefix) {
                unset($_SESSION[$prefix][$name]);
            } else {
                unset($_SESSION[$name]);
            }
        }
    }

    /**
     * 删除所有session变量
     */
    public static function unsetAll ()
    {
        if (session_status() !== PHP_SESSION_ACTIVE ) {
            session_start();
        }

       $_SESSION = [];

        if (isset($_COOKIE[session_name()])) {
            setcookie($_COOKIE[session_name()], '', time()-3600, '/');
        }

        session_destroy();
    }

}