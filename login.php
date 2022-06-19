<?php
    //引入连接数据库和公共文件
    require_once "database.php";
    require_once "common.php";

    //判断action == login，如果url链接上面出现?action=login，则说明用户点击了登录按钮
    if (@$_GET['action'] == 'login'){
        //判断邮箱是否数正确， 如果email输入为空 或者 邮箱的格式有问题， 就会提示："请输入正确的邮箱"
        $email_regular = "/^[a-zA-Z0-9]+[- | a-zA-Z0-9 . _]+@([a-zA-Z0-9]+(-[a-zA-Z0-9]+)?\\.)+[a-z]{2,}$/";
        if ((empty($_POST['email']) || strlen($_POST['email']) < 0) || !preg_match($email_regular, $_POST['email'])){
            alert_back("请输入正确的邮箱");
        }

        //判断密码，输入的密码为空 或者 长度小于6位或者长度大于18位，就会提示 "请输入正确的密码"
        $password_regular = "/^[a-zA-Z0-9_]{6,18}$/";
        if ((empty($_POST['password']) || strlen($_POST['password']) < 0) || !preg_match($password_regular, $_POST['password'])){
            alert_back("请输入正确的密码");
        }

        //获取加密后的密码
        $pass_md5 = md5($_POST['password']);

        //执行查询的sql语句，用于判断当前登录的用户是否存在，不存在就不给登录，存在则将信息存入session和cookie
        //这里的$conn，是database.php文件中获取的数据库连接句柄
        $query = mysqli_query($conn, "select * from user where email = '". $_POST['email'] ."' and password = '". $pass_md5 ."'");
        if ($query){
            //如果查询到用户则数据位1条，如果没有则少于1条数据，说明用户没有找到
            if (mysqli_num_rows($query) < 1){
                alert_back("输入的邮箱或者密码错误，请重新输入");
            }

            //找到则需要将获取到的数据转换成关联数组
            $result = mysqli_fetch_assoc($query);

            //将获取到的数据存入session 和 cookie
            $_SESSION['id'] = $result['id'];
            //过期时间 1天 60 * 60 * 24
            setcookie('email', $result['email'], time() + 60 * 60 * 24);
            alert_jump("登录成功", "index.php");
        }

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登录页</title>
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
    <!--  设置登录整个布局居中  -->
    <div class="login-box">
        <h3 class="title">用户登录</h3>
        <form action="?action=login" method="post" class="login-form">
            <div class="email">
                <p>邮箱：</p>
                <input type="text" name="email" placeholder="请输入正确的邮箱">
            </div>
            <div class="password">
                <p>密码：</p>
                <input type="password" name="password" placeholder="请输入正确的密码">
            </div>
            <div class="login">
                <input type="submit" value="登录">
            </div>
        </form>
    </div>
</body>
</html>