<?php
//引入连接数据库和公共文件
require_once "database.php";
require_once "common.php";

//判断 action == exit
if (@$_GET['action'] == 'exit'){
    exitLogin();
    //退出登录后跳转到首页
    header("location:index.php");
}

?>

<!--  设置头部  -->
<div class="header">
    <div class="top">
        <div class="header-link">
            <h1>
                <a href="index.php">留言板系统</a>
            </h1>
            <a href="index.php">首页</a>
            <a href="leavingmessage.php">留言</a>
            <a href="personal.php">个人中心</a>
        </div>

        <?php
        if (isLogin()){
            //获取登录用户的信息
            $query = mysqli_query($conn, "select * from user where id = '". $_SESSION['id'] ."'");
            $result = mysqli_fetch_assoc($query);
        ?>
            <div class="header-info">
                <p>欢迎 <span style="color: #000"><?php echo $result['username']; ?></span> 登录留言板</p>
                <span class="logout"><a href="?action=exit">退出登录</a></span>
            </div>
        <?php }else{ ?>
            <div class="header-info">
                <span class="logout"><a href="login.php">留言板登录</a></span>
            </div>
        <?php } ?>

    </div>
</div>