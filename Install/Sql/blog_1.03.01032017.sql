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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
---------------------------------------
