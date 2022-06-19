<?php
//引入连接数据库和公共文件
require_once "database.php";
require_once "common.php";

    //判断是否已经登录，需要在各个功能里面进行判断
    //回复留言功能
    //判断action == reply
    if (@$_GET['action'] == 'reply'){
        if (!isLogin()){
            alert_back("您暂无此功能，请联系管理员");
        }
        //需要过滤一下留言的信息，如果不过滤，访客可能在留言的信息里面直接写入前端的代码
        $message = htmlentities($_POST['message']);

        //判断留言是否为空，格式是否正确
        if ((empty($message) || strlen($message) < 0) || strlen($message) > 255){
            alert_back("请输入正确的留言信息，留言长度不能超过255位");
        }

        //更新当前这条留言的回复，需要更新message表中的user_id、reply
        //user_id ，因为是已经登录了，所以可以直接获取存在session中的id
        //reply，则可以获取经过滤的$message
        //注意：这里需要加上where条件，如果不加条件则会将message表中所有数据给更新了，条件则是需要获取当前回复的留言id ，即url中的messageId
        $query = mysqli_query($conn, "update message set user_id = '". $_SESSION['id'] ."', reply = '". $message ."' where id = '". $_GET['messageId'] ."'");
        if ($query){
            //判断操作的留言是否有一条
            if (mysqli_affected_rows($conn) == 1){
                alert_jump("回复留言成功", "index.php");
            }else{
                alert_jump("回复留言失败");
            }
        }
    }

    //删除留言功能
    //判断?delete是否为空
    //因为会出现一些必定的错误，所以需要加上抑制符
    if (!empty(@$_GET['delete'])){
        if (!isLogin()){
            alert_back("您暂无此功能，请联系管理员");
        }
        $query = mysqli_query($conn, "delete from message where id = '" . $_GET['delete'] . "'");
        if ($query){
            //判断是否有一条数据被删除
            if (mysqli_affected_rows($conn) == 1){
                alert_back("删除成功");
            }else{
                alert_back("删除失败");
            }
        }
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>首页</title>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>

    <?php include "header.php"; ?>

    <!--  内容  -->
    <div class="container">
        <div class="main">
            <h2>留言信息</h2>
            <hr style="margin: 20px 0">

            <?php

            //由于这里显示的留言板是可以分页显示的，所以需要规定每页显示多少条数据，这里规定每页显示两条数据
            $showData = 2;

            //还需要规定一次性可以显示多少页，这里规定显示5页
            $showPage = 5;

            //获取总共有多少条数据
            $query = mysqli_query($conn, "select * from message");
            $maxData = mysqli_num_rows($query);

            //这里使用 总数 / 每页显示多少条数据 ==> 总共有多少页【需要进行向上取整】
            $maxPage = ceil($maxData / $showData);

            //获取当前显示第几页， 如果url中存在page这个值，即 ?page=n，则说明当前显示n页，否则为第一页
            //如果n > 总页数 ，则显示总页数
            $page = isset($_GET['page']) ? $_GET['page'] > $maxPage ? $maxPage : $_GET['page'] : 1;

            //获取显示的数据 limit (当前页 - 1) * 每页显示的多少条数据, 每页显示的多少条数据 ，例如：当前第二页 ，即 (2 - 1) * 2 = 2 ，即从第二条数据开始接着显示两条数据 limit 2, 2
            $query = mysqli_query($conn, "select * from message limit " . ($showData * ($page - 1)) . "," . $showData);
            if($query){
                $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
            }

            //分页实现，需要动态的获取页码数和结束页
            //如果当前页 <= 3 ，就显示 1 - 5 的页码 ， 如果总页数不够5页，则显示最大的页码数
            //如果当前页 >= 3 ，就每次显示5页，如果不够5页。则显示最大的页码数
            if ($page <= 3){
                //开始页数
                $begin = 1;
                //结束页数，如果最大页码 > 5则显示5页，否则侠士最大页码
                $end = $maxPage > 5 ? 5 : $maxPage;
            }else{
                //结束页数
                $end = $page + 3 > $maxPage ? $maxPage : $page + 3;
                //开始页数
                $begin = $end - 3;
            }

            ?>

            <?php
            //显示每页的留言
            foreach ($result as $key => $value){
            ?>
<!--        留言的信息   -->
            <div class="part">
                <p class="username"><?php echo $value['visitors_name']; ?>：<span class="time"><?php echo date('Y-m-d H:i:s', $value['create_time']); ?></span></p>
                <p class="content"><?php echo $value['message']; ?></p>

                <?php
                //这里需要判断当前显示的留言是否已经被回复过了
                if ($value['user_id'] && $value['reply']){
                    $query = mysqli_query($conn, "select * from user where id = '". $value['user_id'] ."'");
                    $user_result = mysqli_fetch_assoc($query);

                    echo "<p class='reply'><span>". $user_result['username'] ."</span> 回复的内容：</p>";
                    echo "<p class='content'>". $value['reply'] ."</p>";
                }else{
                    //如果是登录后的用户，用户是可以回复这些留言的
                    if (isLogin()){
                ?>
                        <form action="?action=reply&messageId=<?php echo $value['id']; ?>" method="post">
                            <textarea class="textarea" placeholder="请输入您要回复的留言" name="message"></textarea>
                            <input type="submit" class="submit" value="回复">
                        </form>
                <?php
                    }
                }

                //判断是否登录了，如果是登录的用户，则可以删除一些不良的评论
                if (isLogin()){
                ?>
                    <p class="option">
                        <a href="?delete=<?php echo $value['id'] ?>" class="delete">删除</a>
                    </p>
                <?php
                }
                ?>
            </div>

            <?php } ?>

            <!-- 分页 -->
            <div class="page">
                <ul>
                    <?php
                    //判断是否显示上一页
                    if ($page != 1){
                        echo "<li class='prev' style='width: 80px;'><a href='?page". ($page - 1) ."'>上一页</a></li>";
                    }

                    for ($i = $begin; $i <= $end; $i++){
                        //显示当前页
                        if ($page == $i){
                            echo "<li class='current'><span>". $i ."</span></li>";
                        }else{  //显示其他页码
                            echo "<li><a href='?page=$i'>$i</a></li>";
                        }
                    }

                    //判断是否显示下一页
                    if ($page != $maxPage){
                        echo "<li class='next' style='width: 80px;'><a href='?page". ($page + 1) ."'>下一页</a></li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>