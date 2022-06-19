<?php
//连接的数据库主机
define('HOST', '你的主机');
//数据库用户名
define('USER', '你的用户名');
//数据库密码
define('PASSWORD', '你的密码');
//数据库名
define('DATABASES', 'message_board');

//获取数据库句柄， 这里mysqli_connect加上@是因为这里必定会出现一段报错，但是这段报错不会印象代码的执行
//所以需要用抑制符@来隐藏报错，使用户体验更好
$conn = @mysqli_connect(HOST, USER, PASSWORD, DATABASES);
//判断数据库是否连接
if (mysqli_connect_errno()){
    echo "网络连接错误，请重新尝试";
    exit();
}