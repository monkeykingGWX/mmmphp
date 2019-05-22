<?php

function throwErr (string $errMsg, callable $callback = null)
{
    if (APP_DEBUG) {
        throw new \Exception($errMsg);
    } else {
        $callback();
    }
}

/**
 * 是否是AJAx提交的
 * @return bool
 */
function isAjax(){
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
        return true;
    }else{
        return false;
    }
}
/**
 * 是否是GET提交的
 * @return bool
 */
function isGet(){
    return $_SERVER['REQUEST_METHOD'] == 'GET' ? true : false;
}
/**
 * 是否是POST提交
 * @return int
 */
function isPost() {
    return $_SERVER['REQUEST_METHOD'] == 'POST' ? true : false;
}