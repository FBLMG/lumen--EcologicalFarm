-- ----------------------------------------------------------------------------------------------------------
-- 新闻
-- ----------------------------------------------------------------------------------------------------------

DROP TABLE IF EXISTS `db_news`;
CREATE TABLE `db_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(256) DEFAULT '' COMMENT '新闻标题',
  `images` varchar(255) DEFAULT '' COMMENT '封面',
  `content` text  COMMENT '新闻内容',
  `video` varchar(2000) DEFAULT '' COMMENT '视频',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='新闻表';


-- ----------------------------------------------------------------------------------------------------------
-- 管理员
-- ----------------------------------------------------------------------------------------------------------

DROP TABLE IF EXISTS `db_admin`;
CREATE TABLE `db_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(64) DEFAULT '' COMMENT '用户名', 
  `password` varchar(512) DEFAULT '' COMMENT '密码', 
  `token` varchar(64) DEFAULT '' COMMENT 'Token',
  `token_over_at` int(11) DEFAULT '0' COMMENT 'Token结束时间',
  `token_last_at` int(11) DEFAULT '0' COMMENT 'Token最后时间',
  `power` tinyint(4)  DEFAULT '1' COMMENT '权限 1：超级管理员',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态 1：正常 2：禁用',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员表';

INSERT INTO `db_admin` VALUES (1, 'superadmin','5e036eb847a261232026032752902b1f', '', 0, 0, 1, 1, 1502791971, 0);


-- ----------------------------------------------------------------------------------------------------------
-- 用户表
-- ----------------------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS `db_user`;
CREATE TABLE `db_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `farm_id` int(11) DEFAULT '0' COMMENT '农场ID',
  `wx_open_id` varchar(64) DEFAULT '0' COMMENT '微信OpenID',
  `wx_nickname`varchar(64) DEFAULT '' COMMENT '微信昵称',
  `wx_avatar` varchar(512) DEFAULT '' COMMENT '微信头像',
  `wx_sex` tinyint(4) DEFAULT '0' COMMENT '性别 1：男 2：女 3：未知',
  `power` tinyint(4)  DEFAULT '2' COMMENT '权限 1：农场主 2：会员',
  `level` tinyint(4)  DEFAULT '3' COMMENT '级别 1：皇冠会员 2：钻石会员 3：星级会员',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态 1：正常 2：禁用',
  `token` varchar(64) DEFAULT '' COMMENT 'Token',
  `token_over_at` int(11) DEFAULT '0' COMMENT 'Token结束时间',
  `token_last_at` int(11) DEFAULT '0' COMMENT 'Token最后时间',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_farm_id` (`farm_id`),
  KEY `idx_wx_open_id` (`wx_open_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';


-- ----------------------------------------------------------------------------------------------------------
-- 地区表
-- ----------------------------------------------------------------------------------------------------------

DROP TABLE IF EXISTS `db_region`;
CREATE TABLE `db_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(256) DEFAULT '' COMMENT '地区名称',
  `region_id` int(11) DEFAULT '0' COMMENT '父级ID(0表示父级,非0表示子级)',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='地区表';


-- ----------------------------------------------------------------------------------------------------------
-- 农场
-- ----------------------------------------------------------------------------------------------------------


DROP TABLE IF EXISTS `db_farm`;
CREATE TABLE `db_farm` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) DEFAULT '0' COMMENT '农场主ID',
  `title` varchar(32) DEFAULT '' COMMENT '农场名称',
  `images` varchar(255) DEFAULT '' COMMENT '图片',
  `content` text  COMMENT '内容',
  `video` varchar(2000) DEFAULT '' COMMENT '视频',
  `region_id` int(11) DEFAULT '0' COMMENT '地区ID(父ID)',
  `county_id` int(11) DEFAULT '0' COMMENT '县级ID(子ID)',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_region_id` (`region_id`),
  KEY `idx_county_id` (`county_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='农场表';


-- ----------------------------------------------------------------------------------------------------------
-- 档案表
-- ----------------------------------------------------------------------------------------------------------


DROP TABLE IF EXISTS `db_archives`;
CREATE TABLE `db_archives` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) DEFAULT '0' COMMENT '农场主ID',
  `farm_id` int(11) DEFAULT '0' COMMENT '农场ID',
  `region_id` int(11) DEFAULT '0' COMMENT '地区ID(父ID)',
  `county_id` int(11) DEFAULT '0' COMMENT '县级ID(子ID)',
  `file_name` varchar(256) DEFAULT '' COMMENT '档案名称',
  `product_name` varchar(256) DEFAULT '' COMMENT '产品名称',
  `product_category` tinyint(4) DEFAULT '0' COMMENT '产品类别 1：肉质类 2：果蔬类',
  `growing_area` varchar(256) DEFAULT '' COMMENT '生长产地',
  `enterprise_information` varchar(256) DEFAULT '' COMMENT '企业信息',
  `registration_time` varchar(256) DEFAULT '' COMMENT '登记时间',
  `shelf_life` varchar(256) DEFAULT '' COMMENT '保质期限',
  `feeding_quantity` varchar(256) DEFAULT '' COMMENT '饲养数量(该批次养了有多少只)',
  `authentication_information` varchar(256) DEFAULT '' COMMENT '认证信息',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_farm_id` (`farm_id`),
  KEY `idx_region_id` (`region_id`),
  KEY `idx_county_id` (`county_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='档案表';


-- ----------------------------------------------------------------------------------------------------------
-- 生长情况表
-- ----------------------------------------------------------------------------------------------------------


DROP TABLE IF EXISTS `db_growth_status`;
CREATE TABLE `db_growth_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) DEFAULT '0' COMMENT '农场主ID',
  `farm_id` int(11) DEFAULT '0' COMMENT '农场ID',
  `archives_id` int(11) DEFAULT '0' COMMENT '档案ID',
  `content` text  COMMENT '内容',
  `images` text  COMMENT '图片',
  `video` varchar(2000) DEFAULT '' COMMENT '视频',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_farm_id` (`farm_id`),
  KEY `idx_archives_id` (`archives_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='生长情况表';



-- ----------------------------------------------------------------------------------------------------------
-- 疫苗情况表
-- ----------------------------------------------------------------------------------------------------------


DROP TABLE IF EXISTS `db_vaccine_status`;
CREATE TABLE `db_vaccine_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) DEFAULT '0' COMMENT '农场主ID',
  `farm_id` int(11) DEFAULT '0' COMMENT '农场ID',
  `archives_id` int(11) DEFAULT '0' COMMENT '档案ID',
  `content` text  COMMENT '内容',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_farm_id` (`farm_id`),
  KEY `idx_archives_id` (`archives_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='疫苗情况表';



-- ----------------------------------------------------------------------------------------------------------
-- 饲养情况表
-- ----------------------------------------------------------------------------------------------------------


DROP TABLE IF EXISTS `db_feed_status`;
CREATE TABLE `db_feed_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) DEFAULT '0' COMMENT '农场主ID',
  `farm_id` int(11) DEFAULT '0' COMMENT '农场ID',
  `archives_id` int(11) DEFAULT '0' COMMENT '档案ID',
  `content` text  COMMENT '内容',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_farm_id` (`farm_id`),
  KEY `idx_archives_id` (`archives_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='饲养情况表';


-- ----------------------------------------------------------------------------------------------------------
-- 流通记录表
-- ----------------------------------------------------------------------------------------------------------


DROP TABLE IF EXISTS `db_circulation_record`;
CREATE TABLE `db_circulation_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) DEFAULT '0' COMMENT '农场主ID',
  `farm_id` int(11) DEFAULT '0' COMMENT '农场ID',
  `archives_id` int(11) DEFAULT '0' COMMENT '档案ID',
  `content` text  COMMENT '内容',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_farm_id` (`farm_id`),
  KEY `idx_archives_id` (`archives_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='流通记录表';



