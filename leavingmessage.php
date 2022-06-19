<?php
//引入连接数据库和公共文件
require_once "database.php";
require_once "common.php";

//判断action == message
if (@$_GET['action'] == 'message'){
    //判断留言姓名是否为空，长度是否 > 10
    if ((empty($_POST['visitorName']) || strlen($_POST['visitorName']) < 0) || strlen($_POST['visitorName']) > 10){
        alert_back("请输入正确的留言姓名，留言姓名长单独不能超过10位");
    }

    //判断手机号码是否为空，手机号码的格式是否正确
    $phone_regular = "/^(13[0-9]|14[01456879]|15[0-35-9]|16[2567]|17[0-8]|18[0-9]|19[0-35-9])\d{8}$/";
    if ((empty($_POST['visitorPhone']) || strlen($_POST['visitorPhone']) < 0) || ! preg_match($phone_regular, $_POST['visitorPhone'])){
        alert_back("请输入正确的手机号码");
    }

    //过滤HTML标签
    $message = htmlentities($_POST['message']);

    //判断留言信息是否为空，长度是否超过255位
    if ((empty($message) || strlen($message) < 0) || strlen($message) > 255){
        alert_back("请输入正确的留言信息，留言长度不能超过255位");
    }

    //获取创建时间
    $create_time = time();

    //插入留言信息到数据库
    $query = mysqli_query($conn, "insert into message(visitors_name, visitors_phone, message, create_time) values('". $_POST['visitorName'] ."', '". $_POST['visitorPhone'] ."', '". $message ."', '". $create_time ."')");
    if($query){
        //判断是否插入一条数据
        if (mysqli_affected_rows($conn) == 1){
            alert_jump("留言成功", "index.php");
        }else{
            alert_back("留言失败，请重新尝试!!!");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>留言页</title>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/leavingmessage.css">
</head>
<body>
<?php include "header.php"; ?>
    <div class="container">
        <h3 style="text-align: center">留言板</h3>
        <div class="main">
            <form action="?action=message" method="post">
                <div class="visitor-name">
                    <p>留言人：</p>
                    <input type="text" name="visitorName" placeholder="请输入留言的姓名">
                </div>
                <div class="visitor-phone">
                    <p>留言手机号码：</p>
                    <input type="text" name="visitorPhone" placeholder="请输入留言的手机号码">
                </div>
                <div class="visitor-message">
                    <p>留言信息：</p>
                    <textarea name="message" placeholder="请输入要留言的信息" class="textarea"></textarea>
                </div>
                <div class="submit">
                    <input type="submit" value="留言">
                </div>
            </form>
        </div>
    </div>
</body>
</html>