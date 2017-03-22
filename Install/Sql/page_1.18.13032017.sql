CREATE TABLE IF NOT EXISTS `#_#####_#page` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_layout` int(11) NOT NULL DEFAULT '0',
  `page_url` varchar(255) NOT NULL,
  `page_title` varchar(255) NOT NULL,
  `page_content` text,
  `page_status` tinyint(2) NOT NULL DEFAULT '1',
  `custom_css` text,
  `custom_js` text,
  `created_time` int(11) NOT NULL DEFAULT '0',
  `owner_id` int(11) NOT NULL DEFAULT '0',
  `hide_header_layout` int(1) NOT NULL DEFAULT '0',
  `hide_footer_layout` int(1) NOT NULL DEFAULT '0',
  `is_landing_page` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
---------------------------------------
