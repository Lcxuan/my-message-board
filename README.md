## MyMessageBoard

一个由PHP面向过程制作的留言板项目

### 环境搭建

#### 1.1 创建数据库

```mongodb
create database message_board;
```

#### 1.2 用户表（user）

```mongodb
CREATE TABLE user (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(10) NOT NULL DEFAULT '' COMMENT '用户名',
  email varchar(30) NOT NULL DEFAULT '' COMMENT '用户邮箱',
  password varchar(50) DEFAULT '' COMMENT '用户密码',
  PRIMARY KEY (id)
);
```

#### 1.3 信息表（message）

```mongodb
CREATE TABLE message (
  id int(11) NOT NULL AUTO_INCREMENT,
  visitors_name varchar(10) NOT NULL DEFAULT '' COMMENT '访客名',
  visitors_phone char(11) NOT NULL DEFAULT '' COMMENT '访客手机号码',
  message varchar(255) NOT NULL DEFAULT '' COMMENT '留言信息',
  user_id int(11) DEFAULT NULL COMMENT '用户id，表的外键',
  reply varchar(255) NOT NULL DEFAULT '' COMMENT '用户回复信息',
  create_time varchar(10) NOT NULL DEFAULT '' COMMENT '创建时间',
  PRIMARY KEY (id)
);
```

**注意：也可以引入message_board.sql**

### 修改的参数

database.php文件，修改主机、用户名、密码
