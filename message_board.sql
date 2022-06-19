SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visitors_name` varchar(10) NOT NULL DEFAULT '' COMMENT '访客名',
  `visitors_phone` char(11) NOT NULL DEFAULT '' COMMENT '访客手机号码',
  `message` varchar(255) NOT NULL DEFAULT '' COMMENT '留言信息',
  `user_id` int(11) DEFAULT NULL COMMENT '用户id，表的外键',
  `reply` varchar(255) NOT NULL DEFAULT '' COMMENT '用户回复信息',
  `create_time` varchar(10) NOT NULL DEFAULT '' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(10) NOT NULL DEFAULT '' COMMENT '用户名',
  `email` varchar(30) NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `password` varchar(50) DEFAULT '' COMMENT '用户密码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

