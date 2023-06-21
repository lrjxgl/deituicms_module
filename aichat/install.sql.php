<?php
$content=<<<eof
CREATE TABLE `sky_mod_aichat_bchat` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `prompt` varchar(4096) NOT NULL DEFAULT '提示',
  `userid` int(11) NOT NULL DEFAULT '0',
  `create_status` tinyint(4) NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2023-04-28 22:56:01',
  `stream_status` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `history` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8 COMMENT='聊天记录';
CREATE TABLE `sky_mod_aichat_book` (
  `bookid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL DEFAULT '',
  `prompt` text,
  `content` text,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2023-05-02 07:10:01',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `num` int(10) unsigned NOT NULL DEFAULT '1',
  `answer` text,
  `create_article` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `create_status` tinyint(3) unsigned zerofill NOT NULL DEFAULT '000',
  PRIMARY KEY (`bookid`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='书籍';
CREATE TABLE `sky_mod_aichat_book_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bookid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(128) NOT NULL DEFAULT '',
  `content` text,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2023-05-02 07:10:01',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `prompt` varchar(2046) NOT NULL DEFAULT '',
  `history` mediumtext,
  `create_status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `bookid` (`bookid`)
) ENGINE=InnoDB AUTO_INCREMENT=193 DEFAULT CHARSET=utf8 COMMENT='书籍文章';
CREATE TABLE `sky_mod_aichat_chat_msg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tagid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2023-04-16 22:25:01',
  `content` mediumtext,
  PRIMARY KEY (`id`),
  KEY `tagid` (`tagid`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='消息内容';
CREATE TABLE `sky_mod_aichat_chat_tag` (
  `tagid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2023-04-16 22:25:01',
  PRIMARY KEY (`tagid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='标签';
CREATE TABLE `sky_mod_aichat_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `img_pay_token` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '慢图价格',
  `img_pay_token_quick` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '快图价格',
  `text_pay_token` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '慢文价格',
  `text_pay_token_quick` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '快文价格',
  `apptoken` varchar(64) NOT NULL DEFAULT '' COMMENT 'apptoken',
  `chat_token` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '聊天价格',
  `new_user_token` int(10) unsigned NOT NULL DEFAULT '1000',
  `chat_api_host` varchar(255) NOT NULL DEFAULT '' COMMENT '聊天Host',
  `text_api_host` varchar(255) NOT NULL DEFAULT '' COMMENT '文字Host',
  `img_api_host` varchar(255) NOT NULL DEFAULT '' COMMENT '文生图Host',
  `chat_api_key` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='基本设置';
CREATE TABLE `sky_mod_aichat_img` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `promptid` int(10) unsigned NOT NULL DEFAULT '0',
  `prompt` varchar(4096) NOT NULL DEFAULT '提示',
  `prompt_en` varchar(4096) NOT NULL DEFAULT '',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ishot` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(128) NOT NULL DEFAULT 'static/images/no_image.jpg',
  `imgsdata` varchar(1024) NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2023-04-06 06:07:08',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `fav_num` int(10) unsigned NOT NULL DEFAULT '0',
  `create_status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8 COMMENT='图片生成';
CREATE TABLE `sky_mod_aichat_img2img` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `oimg` varchar(128) NOT NULL DEFAULT '' COMMENT '原图',
  `promptid` int(10) unsigned NOT NULL DEFAULT '0',
  `prompt` varchar(4096) NOT NULL DEFAULT '提示',
  `prompt_en` varchar(4096) NOT NULL DEFAULT '',
  `negative_prompt` varchar(4096) NOT NULL DEFAULT '提示',
  `negative_prompt_en` varchar(4096) NOT NULL DEFAULT '',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ishot` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(128) NOT NULL DEFAULT 'static/images/no_image.jpg',
  `imgsdata` varchar(1024) NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2023-04-06 06:07:08',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `fav_num` int(10) unsigned NOT NULL DEFAULT '0',
  `create_status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='图片生成';
CREATE TABLE `sky_mod_aichat_img2img_prompt` (
  `promptid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `oimg` varchar(128) NOT NULL DEFAULT '' COMMENT '原图',
  `prompt` varchar(4096) NOT NULL DEFAULT '提示',
  `prompt_en` varchar(4096) NOT NULL DEFAULT '',
  `negative_prompt` varchar(4096) NOT NULL DEFAULT '提示',
  `negative_prompt_en` varchar(4096) NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` date NOT NULL DEFAULT '2023-04-06',
  `create_num` int(10) unsigned NOT NULL DEFAULT '0',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `fav_num` int(10) unsigned NOT NULL DEFAULT '0',
  `create_status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`promptid`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8 COMMENT='图片生成提示';
CREATE TABLE `sky_mod_aichat_img2img_task` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `promptid` int(10) unsigned NOT NULL DEFAULT '0',
  `prompt` varchar(4096) NOT NULL DEFAULT '提示',
  `prompt_en` varchar(4096) NOT NULL DEFAULT '',
  `negative_prompt` varchar(4096) NOT NULL DEFAULT '提示',
  `negative_prompt_en` varchar(4096) NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `oimg` varchar(128) NOT NULL DEFAULT '' COMMENT '原图',
  `imgurl` varchar(128) NOT NULL DEFAULT 'static/images/no_image.jpg',
  `imgsdata` varchar(1024) NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2023-04-06 06:07:08',
  `create_status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8 COMMENT='图片生成任务';
CREATE TABLE `sky_mod_aichat_img_prompt` (
  `promptid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `prompt` varchar(4096) NOT NULL DEFAULT '提示',
  `prompt_en` varchar(4096) NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` date NOT NULL DEFAULT '2023-04-06',
  `create_num` int(10) unsigned NOT NULL DEFAULT '0',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `fav_num` int(10) unsigned NOT NULL DEFAULT '0',
  `create_status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`promptid`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8 COMMENT='图片生成提示';
CREATE TABLE `sky_mod_aichat_img_task` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `promptid` int(10) unsigned NOT NULL DEFAULT '0',
  `prompt` varchar(4096) NOT NULL DEFAULT '提示',
  `prompt_en` varchar(4096) NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(128) NOT NULL DEFAULT 'static/images/no_image.jpg',
  `imgsdata` varchar(1024) NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2023-04-06 06:07:08',
  `create_status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8 COMMENT='图片生成任务';
CREATE TABLE `sky_mod_aichat_imgscale` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `oimg` varchar(128) NOT NULL DEFAULT '' COMMENT '原图',
  `promptid` int(10) unsigned NOT NULL DEFAULT '0',
  `prompt` varchar(4096) NOT NULL DEFAULT '提示',
  `prompt_en` varchar(4096) NOT NULL DEFAULT '',
  `negative_prompt` varchar(4096) NOT NULL DEFAULT '提示',
  `negative_prompt_en` varchar(4096) NOT NULL DEFAULT '',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ishot` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(128) NOT NULL DEFAULT 'static/images/no_image.jpg',
  `imgsdata` varchar(1024) NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2023-04-06 06:07:08',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `fav_num` int(10) unsigned NOT NULL DEFAULT '0',
  `create_status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='图片生成';
CREATE TABLE `sky_mod_aichat_imgscale_prompt` (
  `promptid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `oimg` varchar(128) NOT NULL DEFAULT '' COMMENT '原图',
  `prompt` varchar(4096) NOT NULL DEFAULT '提示',
  `prompt_en` varchar(4096) NOT NULL DEFAULT '',
  `negative_prompt` varchar(4096) NOT NULL DEFAULT '提示',
  `negative_prompt_en` varchar(4096) NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` date NOT NULL DEFAULT '2023-04-06',
  `create_num` int(10) unsigned NOT NULL DEFAULT '0',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `fav_num` int(10) unsigned NOT NULL DEFAULT '0',
  `create_status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`promptid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='图片生成提示';
CREATE TABLE `sky_mod_aichat_imgscale_task` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `promptid` int(10) unsigned NOT NULL DEFAULT '0',
  `prompt` varchar(4096) NOT NULL DEFAULT '提示',
  `prompt_en` varchar(4096) NOT NULL DEFAULT '',
  `negative_prompt` varchar(4096) NOT NULL DEFAULT '提示',
  `negative_prompt_en` varchar(4096) NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `oimg` varchar(128) NOT NULL DEFAULT '' COMMENT '原图',
  `imgurl` varchar(128) NOT NULL DEFAULT 'static/images/no_image.jpg',
  `imgsdata` varchar(1024) NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2023-04-06 06:07:08',
  `create_status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='图片生成任务';
CREATE TABLE `sky_mod_aichat_onetask` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskname` varchar(32) NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2023-04-12 05:17:18',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='新手任务';
CREATE TABLE `sky_mod_aichat_prompt` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(32) NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `description` varchar(1024) NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2023-03-23 01:02:03',
  `updatetime` datetime NOT NULL DEFAULT '2023-03-23 01:02:03',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `fav_num` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='提示词';
CREATE TABLE `sky_mod_aichat_prompt_category` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='提示词类别';
CREATE TABLE `sky_mod_aichat_queue` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `action` varchar(32) NOT NULL DEFAULT '动作',
  `prompt` varchar(4096) NOT NULL DEFAULT '提示',
  `finishdata` varchar(4096) NOT NULL DEFAULT '完成动作',
  `createtime` date NOT NULL DEFAULT '2023-04-06',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='队列';
CREATE TABLE `sky_mod_aichat_text` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(225) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `promptid` int(10) unsigned NOT NULL DEFAULT '0',
  `description` varchar(225) NOT NULL DEFAULT '',
  `prompt` varchar(4096) NOT NULL DEFAULT '提示',
  `prompt_en` varchar(4096) NOT NULL DEFAULT '',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ishot` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(128) NOT NULL DEFAULT 'static/images/no_image.jpg',
  `imgsdata` varchar(1024) NOT NULL DEFAULT '',
  `content` mediumtext,
  `chatdata` mediumtext,
  `createtime` datetime NOT NULL DEFAULT '2023-04-06 06:07:08',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `fav_num` int(10) unsigned NOT NULL DEFAULT '0',
  `create_status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8 COMMENT='文章生成';
CREATE TABLE `sky_mod_aichat_text_prompt` (
  `promptid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `prompt` varchar(4096) NOT NULL DEFAULT '提示',
  `prompt_en` varchar(4096) NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` date NOT NULL DEFAULT '2023-04-06',
  `create_num` int(10) unsigned NOT NULL DEFAULT '0',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `fav_num` int(10) unsigned NOT NULL DEFAULT '0',
  `create_status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`promptid`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8 COMMENT='文章生成提示';
CREATE TABLE `sky_mod_aichat_text_task` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(225) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `promptid` int(10) unsigned NOT NULL DEFAULT '0',
  `prompt` varchar(4096) NOT NULL DEFAULT '提示',
  `prompt_en` varchar(4096) NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext,
  `chatdata` mediumtext,
  `createtime` datetime NOT NULL DEFAULT '2023-04-06 06:07:08',
  `create_status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8 COMMENT='文章生成任务';
CREATE TABLE `sky_mod_aichat_token` (
  `tokenid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `num` int(10) unsigned NOT NULL DEFAULT '0',
  `price` decimal(11,2) NOT NULL DEFAULT '0.00',
  `createtime` datetime NOT NULL DEFAULT '2023-04-12 03:34:01',
  `orderindex` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tokenid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='token';
CREATE TABLE `sky_mod_aichat_token_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2023-04-12 03:34:01',
  `userid` int(11) NOT NULL DEFAULT '0',
  `num` int(11) NOT NULL DEFAULT '0',
  `content` varchar(225) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='token记录';
CREATE TABLE `sky_mod_aichat_token_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tokenid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2023-04-12 03:34:01',
  `userid` int(11) NOT NULL DEFAULT '0',
  `num` int(10) unsigned NOT NULL DEFAULT '0',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `recharge_id` int(10) unsigned NOT NULL DEFAULT '0',
  `paytype` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`orderid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='token购买订单';
CREATE TABLE `sky_mod_aichat_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `token` bigint(20) unsigned NOT NULL DEFAULT '0',
  `income` decimal(11,2) NOT NULL DEFAULT '0.00',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00',
  `chatgpt_api_key` varchar(64) NOT NULL DEFAULT '',
  `vip_etime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'vip到期时间',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户';
CREATE TABLE `sky_mod_aichat_vip` (
  `tokenid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '天数',
  `price` decimal(11,2) NOT NULL DEFAULT '0.00',
  `createtime` datetime NOT NULL DEFAULT '2023-04-12 03:34:01',
  `orderindex` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tokenid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='vip';
CREATE TABLE `sky_mod_aichat_vip_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tokenid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2023-04-12 03:34:01',
  `userid` int(11) NOT NULL DEFAULT '0',
  `num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '天数',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `recharge_id` int(10) unsigned NOT NULL DEFAULT '0',
  `paytype` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`orderid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='vip购买订单';

eof;
?>