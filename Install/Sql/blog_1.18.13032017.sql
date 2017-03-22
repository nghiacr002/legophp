CREATE TABLE IF NOT EXISTS `#_#####_#blog` (
  `blog_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `blog_title` varchar(255) NOT NULL,
  `slug` varchar(400) NOT NULL,
  `sort_description` varchar(300) NOT NULL,
  `blog_description` text,
  `cover_image` varchar(255) DEFAULT NULL,
  `owner_id` int(11) NOT NULL DEFAULT '0',
  `blog_status` int(2) NOT NULL DEFAULT '0',
  `created_time` int(11) DEFAULT NULL,
  `updated_time` int(11) DEFAULT NULL,
  `layout_id` int(11) NOT NULL DEFAULT '0',
  `is_featured` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`blog_id`),
  KEY `category_id` (`category_id`),
  KEY `owner_id` (`owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
INSERT INTO `#_#####_#widgets` VALUES("1","blog.featured_blog","blog/featured","blog","{\"title\":{\"type\":\"text\",\"value\":\"\",\"title\":\"core.title\"},\"limit\":{\"type\":\"text\",\"value\":\"\",\"title\":\"core.limit\"}}","1",""),
("2","blog.recent_blog","blog/recent","blog","{\"title\":{\"type\":\"text\",\"value\":\"\",\"title\":\"core.title\"},\"limit\":{\"type\":\"text\",\"value\":\"\",\"title\":\"core.limit\"}}","1","");
---------------------------------------

---------------------------------------
INSERT INTO `#_#####_#permission` VALUES("33","Blog","can_add_blog","blog.can_add_blog_title","blog.can_add_blog_description","1","0",""),
("32","Blog","can_edit_blog","blog.can_edit_blog_title","blog.can_edit_blog_description","1","0",""),
("31","Blog","can_access_blog","blog.can_access_blog_title","blog.can_access_blog_description","1","0","");
---------------------------------------

---------------------------------------
INSERT INTO `#_#####_#module` VALUES("16","blog","Blog","Blog News","1.0","lego","0","1","");
---------------------------------------
