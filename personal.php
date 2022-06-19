<?php
    //引入连接数据库和公共文件
    require_once "database.php";
    require_once "common.php";

    //由于个人中心，是需要登录后才能进行访问的，所以需要进行判断
    if (!isLogin()){
        alert_jump("您没有权限访问，请联系管理员", "index.php");
    }

    //需要显示用户名还有邮箱
    $query = mysqli_query($conn, "select username, email from user where id = '" . $_SESSION['id'] . "'");
    if($query){
        $result = mysqli_fetch_assoc($query);
    }

    //实现修改密码的功能
    //判断action == info
    if (@$_GET['action'] == 'info'){
        //判断密码和确认密码的格式
        $pass_regular = '/^[a-zA-Z0-9_]{6,18}$/';
        //判断密码
        if ((empty($_POST['password']) || strlen($_POST['password']) < 0) || !preg_match($pass_regular, $_POST['password'])){
            alert_back("请输入正确的密码");
        }
        //判断确认密码
        if ((empty($_POST['confirmPassword']) || strlen($_POST['confirmPassword']) < 0) || !preg_match($pass_regular, $_POST['confirmPassword'])){
            alert_back("请输入正确的密码");
        }

        //判断密码和确认密码是否一致
        if ($_POST['password'] != $_POST['confirmPassword']){
            alert_back("输入的密码和确认密码不正确，请重新输入!!!");
        }

        //获取加密后的密码
        $pass_md5 = md5($_POST['password']);

        //修改用户密码
        $query = mysqli_query($conn, "update user set password = '". $pass_md5 ."' where id = '". $_SESSION['id'] ."'");
        //判断修改的数据是否有一条
        if (mysqli_affected_rows($conn) == 1){
            //修改完后退出登录
            exitLogin();

            //跳转到登录页
            header("location:login.php");
        }else{
            alert_back("密码修改失败，请重新尝试！！！");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>个人中心</title>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/personal.css">
</head>
<body>
    <?php include "header.php"; ?>
    <div class="container">
        <h3 style="text-align: center">个人中心</h3>
        <div class="main">
            <form action="?action=info" method="post">
                <div class="username">
                    <p>用户名：</p>
                    <input type="text" name="username" placeholder="请输入用户名" value="<?php echo $result['username'] ?>" readonly>
                </div>
                <div class="email">
                    <p>邮箱：</p>
                    <input type="text" name="email" placeholder="请输入邮箱" value="<?php echo $result['email'] ?>" readonly>
                </div>
                <div class="password">
                    <p>密码：</p>
                    <input type="password" name="password" placeholder="请输入正确的密码">
                </div>
                <div class="confirm-password">
                    <p>确认密码：</p>
                    <input type="password" name="confirmPassword" placeholder="请输入正确的确认密码">
                </div>
                <div class="submit">
                    <input type="submit" value="修改">
                </div>
            </form>
        </div>
    </div>
</body>
</html>