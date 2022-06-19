<?php
//开启session
session_start();

/**
 * 跳转页面
 * @param $info 提示的信息
 * @param $url 跳转的路径
 */
function alert_jump($info, $url){
    echo "<script>alert('$info');location.href='$url'</script>";
    exit();
}

/**
 * 返回上级，并提示
 * @param $info 提示的信息
 */
function alert_back($info){
    echo "<script>alert('$info');history.back()</script>";
    exit();
}

/**
 * 判断是否登录
 * @return bool
 */
function isLogin(){
    if (isset($_SESSION['id']) && isset($_COOKIE['email'])){
        return true;
    }
    return false;
}

/**
 * 退出登录
 */
function exitLogin(){
    $_COOKIE = [];
    session_destroy();
}