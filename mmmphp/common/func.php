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

/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return string
 */
function dump ($var, $echo=true, $label=null, $strict=true)
{
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }else {
        return $output;
    }

}

function ajaxReturn ($data)
{
    echo json_encode($data);
    exit;
}

function htmlFilter ($data)
{
    if (is_array($data)) {
        foreach ($data as $k => $v) {
            $data[$k] = htmlFilter($v);
        }

        return $data;
    } else {
        return htmlspecialchars($data);
    }
}

/**
 * 将[ ['id'=>1,'name'=> 'gwx'],..] 转换为 ['1'=>['id'=>1,'name'=> 'gwx'],...]
 * @param array $arr
 */
function arrayFun1 (array $arr, string $pk)
{
    $tmpArr = [];
    foreach ($arr as $k => $v) {
        $tmpArr[$v[$pk]] = $v;
    }

    return $tmpArr;
}

/**
 * 页面跳转
 * @param $url
 */
function jump ($url)
{
    header("Location:$url");
    exit;
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip($type = 0,$adv=false) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if($adv){
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

/**
 * 获取请求参数post或get
 * @param $paramKey
 * @return string
 */
function requrstParam ($paramKey)
{
    if (isset($_POST[$paramKey])) {
        return $_POST[$paramKey];
    }

    if (isset($_GET[$paramKey])) {
        return $_GET[$paramKey];
    }

    return '';
}

/**
 * 获取当天凌晨时间戳
 * @param $timestamp
 * @return false|int
 */
function getTodayTime ($timestamp)
{
    return strtotime(date('Y-m-d', $timestamp));
}