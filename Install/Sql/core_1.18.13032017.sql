CREATE TABLE IF NOT EXISTS `#_#####_#category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `is_active` int(1) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `category_type` varchar(20) NOT NULL DEFAULT 'system',
  `params` text,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#country` (
  `country_id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(2) NOT NULL,
  `country_name` varchar(255) NOT NULL,
  `country_phone` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=233 DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
INSERT INTO `#_#####_#country` VALUES("1","AF","Afghanistan","+93"),
("2","AL","Albania","+355"),
("3","DZ","Algeria","+213"),
("4","AS","American Samoa","+1"),
("5","AD","Andorra","+376"),
("6","AO","Angola","+244"),
("7","AI","Anguilla","+1"),
("8","AG","Antigua","+1"),
("9","AR","Argentina","+54"),
("10","AM","Armenia","+374"),
("11","AW","Aruba","+297"),
("12","AU","Australia","+61"),
("13","AT","Austria","+43"),
("14","AZ","Azerbaijan","+994"),
("15","BH","Bahrain","+973"),
("16","BD","Bangladesh","+880"),
("17","BB","Barbados","+1"),
("18","BY","Belarus","+375"),
("19","BE","Belgium","+32"),
("20","BZ","Belize","+501"),
("21","BJ","Benin","+229"),
("22","BM","Bermuda","+1"),
("23","BT","Bhutan","+975"),
("24","BO","Bolivia","+591"),
("25","BA","Bosnia and Herzegovina","+387"),
("26","BW","Botswana","+267"),
("27","BR","Brazil","+55"),
("28","IO","British Indian Ocean Territory","+246"),
("29","VG","British Virgin Islands","+1"),
("30","BN","Brunei","+673"),
("31","BG","Bulgaria","+359"),
("32","BF","Burkina Faso","+226"),
("33","MM","Burma Myanmar","+95"),
("34","BI","Burundi","+257"),
("35","KH","Cambodia","+855"),
("36","CM","Cameroon","+237"),
("37","CA","Canada","+1"),
("38","CV","Cape Verde","+238"),
("39","KY","Cayman Islands","+1"),
("40","CF","Central African Republic","+236"),
("41","TD","Chad","+235"),
("42","CL","Chile","+56"),
("43","CN","China","+86"),
("44","CO","Colombia","+57"),
("45","KM","Comoros","+269"),
("46","CK","Cook Islands","+682"),
("47","CR","Costa Rica","+506"),
("48","CI","Côte d\'Ivoire","+225"),
("49","HR","Croatia","+385"),
("50","CU","Cuba","+53"),
("51","CY","Cyprus","+357"),
("52","CZ","Czech Republic","+420"),
("53","CD","Democratic Republic of Congo","+243"),
("54","DK","Denmark","+45"),
("55","DJ","Djibouti","+253"),
("56","DM","Dominica","+1"),
("57","DO","Dominican Republic","+1"),
("58","EC","Ecuador","+593"),
("59","EG","Egypt","+20"),
("60","SV","El Salvador","+503"),
("61","GQ","Equatorial Guinea","+240"),
("62","ER","Eritrea","+291"),
("63","EE","Estonia","+372"),
("64","ET","Ethiopia","+251"),
("65","FK","Falkland Islands","+500"),
("66","FO","Faroe Islands","+298"),
("67","FM","Federated States of Micronesia","+691"),
("68","FJ","Fiji","+679"),
("69","FI","Finland","+358"),
("70","FR","France","+33"),
("71","GF","French Guiana","+594"),
("72","PF","French Polynesia","+689"),
("73","GA","Gabon","+241"),
("74","GE","Georgia","+995"),
("75","DE","Germany","+49"),
("76","GH","Ghana","+233"),
("77","GI","Gibraltar","+350"),
("78","GR","Greece","+30"),
("79","GL","Greenland","+299"),
("80","GD","Grenada","+1"),
("81","GP","Guadeloupe","+590"),
("82","GU","Guam","+1"),
("83","GT","Guatemala","+502"),
("84","GN","Guinea","+224"),
("85","GW","Guinea-Bissau","+245"),
("86","GY","Guyana","+592"),
("87","HT","Haiti","+509"),
("88","HN","Honduras","+504"),
("89","HK","Hong Kong","+852"),
("90","HU","Hungary","+36"),
("91","IS","Iceland","+354"),
("92","IN","India","+91"),
("93","ID","Indonesia","+62"),
("94","IR","Iran","+98"),
("95","IQ","Iraq","+964"),
("96","IE","Ireland","+353"),
("97","IL","Israel","+972"),
("98","IT","Italy","+39"),
("99","JM","Jamaica","+1"),
("100","JP","Japan","+81");
---------------------------------------

---------------------------------------
INSERT INTO `#_#####_#country` VALUES("101","JO","Jordan","+962"),
("102","KZ","Kazakhstan","+7"),
("103","KE","Kenya","+254"),
("104","KI","Kiribati","+686"),
("105","XK","Kosovo","+381"),
("106","KW","Kuwait","+965"),
("107","KG","Kyrgyzstan","+996"),
("108","LA","Laos","+856"),
("109","LV","Latvia","+371"),
("110","LB","Lebanon","+961"),
("111","LS","Lesotho","+266"),
("112","LR","Liberia","+231"),
("113","LY","Libya","+218"),
("114","LI","Liechtenstein","+423"),
("115","LT","Lithuania","+370"),
("116","LU","Luxembourg","+352"),
("117","MO","Macau","+853"),
("118","MK","Macedonia","+389"),
("119","MG","Madagascar","+261"),
("120","MW","Malawi","+265"),
("121","MY","Malaysia","+60"),
("122","MV","Maldives","+960"),
("123","ML","Mali","+223"),
("124","MT","Malta","+356"),
("125","MH","Marshall Islands","+692"),
("126","MQ","Martinique","+596"),
("127","MR","Mauritania","+222"),
("128","MU","Mauritius","+230"),
("129","YT","Mayotte","+262"),
("130","MX","Mexico","+52"),
("131","MD","Moldova","+373"),
("132","MC","Monaco","+377"),
("133","MN","Mongolia","+976"),
("134","ME","Montenegro","+382"),
("135","MS","Montserrat","+1"),
("136","MA","Morocco","+212"),
("137","MZ","Mozambique","+258"),
("138","NA","Namibia","+264"),
("139","NR","Nauru","+674"),
("140","NP","Nepal","+977"),
("141","NL","Netherlands","+31"),
("142","AN","Netherlands Antilles","+599"),
("143","NC","New Caledonia","+687"),
("144","NZ","New Zealand","+64"),
("145","NI","Nicaragua","+505"),
("146","NE","Niger","+227"),
("147","NG","Nigeria","+234"),
("148","NU","Niue","+683"),
("149","NF","Norfolk Island","+672"),
("150","KP","North Korea","+850"),
("151","MP","Northern Mariana Islands","+1"),
("152","NO","Norway","+47"),
("153","OM","Oman","+968"),
("154","PK","Pakistan","+92"),
("155","PW","Palau","+680"),
("156","PS","Palestine","+970"),
("157","PA","Panama","+507"),
("158","PG","Papua New Guinea","+675"),
("159","PY","Paraguay","+595"),
("160","PE","Peru","+51"),
("161","PH","Philippines","+63"),
("162","PL","Poland","+48"),
("163","PT","Portugal","+351"),
("164","PR","Puerto Rico","+1"),
("165","QA","Qatar","+974"),
("166","CG","Republic of the Congo","+242"),
("167","RE","Réunion","+262"),
("168","RO","Romania","+40"),
("169","RU","Russia","+7"),
("170","RW","Rwanda","+250"),
("171","BL","Saint Barthélemy","+590"),
("172","SH","Saint Helena","+290"),
("173","KN","Saint Kitts and Nevis","+1"),
("174","MF","Saint Martin","+590"),
("175","PM","Saint Pierre and Miquelon","+508"),
("176","VC","Saint Vincent and the Grenadines","+1"),
("177","WS","Samoa","+685"),
("178","SM","San Marino","+378"),
("179","ST","São Tomé and Príncipe","+239"),
("180","SA","Saudi Arabia","+966"),
("181","SN","Senegal","+221"),
("182","RS","Serbia","+381"),
("183","SC","Seychelles","+248"),
("184","SL","Sierra Leone","+232"),
("185","SG","Singapore","+65"),
("186","SK","Slovakia","+421"),
("187","SI","Slovenia","+386"),
("188","SB","Solomon Islands","+677"),
("189","SO","Somalia","+252"),
("190","ZA","South Africa","+27"),
("191","KR","South Korea","+82"),
("192","ES","Spain","+34"),
("193","LK","Sri Lanka","+94"),
("194","LC","St. Lucia","+1"),
("195","SD","Sudan","+249"),
("196","SR","Suriname","+597"),
("197","SZ","Swaziland","+268"),
("198","SE","Sweden","+46"),
("199","CH","Switzerland","+41"),
("200","SY","Syria","+963");
---------------------------------------

---------------------------------------
INSERT INTO `#_#####_#country` VALUES("201","TW","Taiwan","+886"),
("202","TJ","Tajikistan","+992"),
("203","TZ","Tanzania","+255"),
("204","TH","Thailand","+66"),
("205","BS","The Bahamas","+1"),
("206","GM","The Gambia","+220"),
("207","TL","Timor-Leste","+670"),
("208","TG","Togo","+228"),
("209","TK","Tokelau","+690"),
("210","TO","Tonga","+676"),
("211","TT","Trinidad and Tobago","+1"),
("212","TN","Tunisia","+216"),
("213","TR","Turkey","+90"),
("214","TM","Turkmenistan","+993"),
("215","TC","Turks and Caicos Islands","+1"),
("216","TV","Tuvalu","+688"),
("217","UG","Uganda","+256"),
("218","UA","Ukraine","+380"),
("219","AE","United Arab Emirates","+971"),
("220","GB","United Kingdom","+44"),
("221","US","United States","+1"),
("222","UY","Uruguay","+598"),
("223","VI","US Virgin Islands","+1"),
("224","UZ","Uzbekistan","+998"),
("225","VU","Vanuatu","+678"),
("226","VA","Vatican City","+39"),
("227","VE","Venezuela","+58"),
("228","VN","Vietnam","+84"),
("229","WF","Wallis and Futuna","+681"),
("230","YE","Yemen","+967"),
("231","ZM","Zambia","+260"),
("232","ZW","Zimbabwe","+263");
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#hashtag` (
  `hashtag_id` int(11) NOT NULL AUTO_INCREMENT,
  `hashtag_name` varchar(255) NOT NULL,
  `hashtag_code` varchar(255) NOT NULL DEFAULT '0',
  `item_id` int(255) NOT NULL DEFAULT '0',
  `item_type` varchar(50) NOT NULL DEFAULT 'system',
  PRIMARY KEY (`hashtag_id`),
  FULLTEXT KEY `hashtag_code` (`hashtag_code`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#hashtag_stats` (
  `stats_id` int(11) NOT NULL AUTO_INCREMENT,
  `hashtag_code` varchar(255) NOT NULL,
  `hashtag_counter` int(11) NOT NULL DEFAULT '0',
  `last_updated` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`stats_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#hook` (
  `hook_id` int(11) NOT NULL AUTO_INCREMENT,
  `hook_name` varchar(255) NOT NULL,
  `module_name` varchar(55) NOT NULL,
  `is_active` tinyint(2) NOT NULL DEFAULT '1',
  `params` text,
  PRIMARY KEY (`hook_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
INSERT INTO `#_#####_#hook` VALUES("1","application_init_start","core","1",""),
("2","application_init_end","core","1","");
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#item_ordering` (
  `ordering_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL DEFAULT '0',
  `item_type` varchar(55) NOT NULL DEFAULT 'machine',
  `item_ordering_value` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ordering_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#language` (
  `language_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_name` varchar(255) NOT NULL,
  `language_code` varchar(3) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT '1',
  `is_default` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
INSERT INTO `#_#####_#language` VALUES("1","English (US)","en","1","1"),
("2","Tiếng Việt","vi","1","0");
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#language_patch` (
  `phrase_id` int(11) NOT NULL AUTO_INCREMENT,
  `var_name` varchar(500) NOT NULL,
  `language_code` varchar(3) NOT NULL,
  `value` text,
  PRIMARY KEY (`phrase_id`),
  KEY `language_code` (`language_code`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
INSERT INTO `#_#####_#language_patch` VALUES("1","language.nice_a","en","nice a"),
("2","language.nice_a","vi","dep a"),
("3","core.phrase_var","en","Phrase Var"),
("4","core.phrase_var","vi","Phrase Var"),
("5","core.phrase_value","en","Value"),
("6","core.phrase_value","vi","Value"),
("7","language.nice_1","en","2"),
("8","language.nice_1","vi","33"),
("11","language.aa","en","f22"),
("12","language.aa","vi","2323");
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#layout` (
  `layout_id` int(11) NOT NULL AUTO_INCREMENT,
  `layout_title` varchar(255) NOT NULL,
  `layout_name` varchar(50) NOT NULL,
  `header` int(1) NOT NULL DEFAULT '1',
  `footer` int(1) NOT NULL DEFAULT '1',
  `layout_content` text,
  `is_template_default` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`layout_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
INSERT INTO `#_#####_#layout` VALUES("1","Page 3 Columns","Layout3Column.tpl","1","0","","0"),
("2","Page 3 Columns - Big Middle","Layout3ColumnMiddleBig.tpl","1","1","","0"),
("3","Page 3 Columns - Big Middle (1)","Layout3ColumnMiddleBig1.tpl","1","1","","0"),
("4","Page 3 Columns - Big Middle (2)","Layout3ColumnMiddleBig2.tpl","1","1","","1"),
("5","Page 2 Columns - Big Left","Layout2ColumnBigLeft.tpl","1","1","","0"),
("6","Page 2 Columns - Big Right","Layout2ColumnBigRight.tpl","1","1","","0"),
("7","Page 2 Columns","Layout2Column.tpl","1","1","","0"),
("8","nice to","layout-design-1478576321.tpl","1","1","		        <div class=\"row-design\">
    <div class=\"page-location-header\">
        <span>
            Global Header
            <span>
                <a href=\"javascript:void(0);\" class=\"hide_on_this_page h-header\" df=\"0\" t=\"header\">
                 
                	Hide on this page
                                </a>
            </span>
        </span>
    </div>
    <div class=\"col-md-12  col-sm-12 main-contain-wrapper frame-wrapper ui-sortable\" id=\"page-design-skeleton\">
       			
    <div class=\"frame-item col-md-11  col-sm-11 col-xs-11 frame-wrapper-child\">{{ Location(1) }}</div><div class=\"frame-item col-md-9  col-sm-9 col-xs-9 frame-wrapper-child\">{{ Location(2) }}</div><div class=\"frame-item col-md-5  col-sm-5 col-xs-5 frame-wrapper-child\">{{ Location(3) }}</div></div>
    <div class=\"page-location-footer\">
        <span>
            Global Footer
            <span>
                <a href=\"javascript:void(0);\" class=\"hide_on_this_page h-footer\" df=\"0\" t=\"footer\">
                	 
	                	Hide on this page
	                                </a>
            </span>
        </span>
    </div>
</div>
		    ","0");
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#layout_design` (
  `pw_id` int(11) NOT NULL AUTO_INCREMENT,
  `layout_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `widget_id` int(11) NOT NULL,
  `param_values` text,
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pw_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#layout_widgets` (
  `pw_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL DEFAULT '0',
  `item_type` varchar(50) NOT NULL,
  `widget_id` int(11) NOT NULL DEFAULT '0',
  `location_id` int(11) NOT NULL DEFAULT '0',
  `layout_id` int(11) NOT NULL DEFAULT '0',
  `param_values` text,
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pw_id`),
  KEY `item_id` (`item_id`,`item_type`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#media` (
  `media_id` int(11) NOT NULL AUTO_INCREMENT,
  `media_title` varchar(255) NOT NULL,
  `owner_id` int(11) NOT NULL DEFAULT '0',
  `destination` varchar(255) NOT NULL,
  `uploaded_time` int(11) NOT NULL DEFAULT '0',
  `file_type` varchar(15) DEFAULT NULL,
  `item_id` int(11) NOT NULL DEFAULT '0',
  `item_type` varchar(55) NOT NULL DEFAULT 'system',
  `meta_file` text,
  PRIMARY KEY (`media_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#meta_tags` (
  `meta_tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `meta_tag` varchar(255) NOT NULL,
  `meta_content` text,
  `meta_group` varchar(50) NOT NULL DEFAULT 'default',
  `params` text,
  `item_type` varchar(55) NOT NULL,
  `item_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`meta_tag_id`)
) ENGINE=InnoDB AUTO_INCREMENT=235 DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(255) NOT NULL,
  `menu_type` varchar(50) NOT NULL DEFAULT 'main_menu',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `is_active` tinyint(2) NOT NULL DEFAULT '1',
  `url` varchar(255) NOT NULL,
  `params` int(11) DEFAULT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#module` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(55) NOT NULL,
  `module_title` varchar(255) NOT NULL,
  `module_description` varchar(500) DEFAULT NULL,
  `module_version` varchar(10) NOT NULL,
  `owner` varchar(255) DEFAULT NULL,
  `is_core` tinyint(2) NOT NULL DEFAULT '0',
  `is_active` int(11) NOT NULL DEFAULT '0',
  `params` text,
  PRIMARY KEY (`module_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
INSERT INTO `#_#####_#module` VALUES("1","core","Simple App Core","Simple App Core","1.0","System","1","1",""),
("3","page","Dynamic Page HTML","Dynamic Page HTML","1.0","System ","1","1",""),
("4","theme","Manage Theme","Manage Theme","1.0","System","1","1",""),
("7","user","User","Manage User","1.0","System","1","1","");
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#module_controller` (
  `controller_id` int(11) NOT NULL AUTO_INCREMENT,
  `controller_name` varchar(255) NOT NULL,
  `router_name` varchar(255) NOT NULL,
  `module_name` varchar(55) NOT NULL,
  `layout_id` int(11) NOT NULL DEFAULT '0',
  `hide_header_layout` int(1) NOT NULL DEFAULT '0',
  `hide_footer_layout` int(1) NOT NULL DEFAULT '0',
  `custom_js` text,
  `custom_css` text,
  PRIMARY KEY (`controller_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#note` (
  `note_id` int(11) NOT NULL AUTO_INCREMENT,
  `note_type` varchar(50) NOT NULL DEFAULT 'system',
  `note_title` varchar(255) NOT NULL,
  `note_description` text,
  `last_update` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`note_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
INSERT INTO `#_#####_#note` VALUES("1","system","core.note_title","GNU GENERAL PUBLIC LICENSE
Version 3, 29 June 2007
Copyright (C) 2007 Free Software Foundation, Inc. <http://fsf.org/>
Everyone is permitted to copy and distribute verbatim copies
of this license document, but changing it is not allowed.

","1476343421");
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#notification` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `item_type` varchar(50) DEFAULT NULL,
  `created_time` int(11) DEFAULT NULL,
  `params` text,
  `user_id` int(11) NOT NULL,
  `is_read` int(3) NOT NULL DEFAULT '0',
  `is_push` int(3) NOT NULL DEFAULT '0',
  `module` varchar(55) NOT NULL DEFAULT 'core',
  PRIMARY KEY (`notification_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
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

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#permission` (
  `permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(45) DEFAULT NULL,
  `var_name` varchar(255) DEFAULT NULL,
  `permission_title` varchar(255) NOT NULL,
  `description` text,
  `is_active` int(11) NOT NULL DEFAULT '0',
  `is_default` int(3) DEFAULT '0',
  `params` text,
  PRIMARY KEY (`permission_id`)
) ENGINE=MyISAM AUTO_INCREMENT=132 DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
INSERT INTO `#_#####_#permission` VALUES("1","Core","can_access_admincp","core.can_access_admincp_title","core.can_access_admincp_description","1","0",""),
("2","Core","can_access_maintenance_page","core.can_access_maintenance_page_title","core.can_access_maintenance_page_description","1","0",""),
("3","Core","can_update_setting","core.can_update_setting_title","core.can_update_setting_description","1","0",""),
("4","Core","can_access_menu_page","core.can_access_menu_page_title","core.can_access_menu_page_description","1","0",""),
("5","Core","can_access_group_page","core.can_access_group_page_title","core.can_access_group_page_description","1","0",""),
("6","Core","can_access_module_page","core.can_access_module_page_title","core.can_access_module_page_description","1","0",""),
("7","Core","can_access_language_page","core.can_access_language_page_title","core.can_access_language_page_description","1","0",""),
("8","Core","can_access_module_user","core.can_access_module_user_title","core.can_access_module_user_description","1","0",""),
("9","Core","can_add_user","core.can_add_user_title","core.can_add_user_description","1","0",""),
("10","Core","can_delete_user","core.can_delete_user_title","core.can_delete_user_description","1","0",""),
("15","Core","can_access_theme_page","core.can_access_theme_page_title","core.can_access_theme_page_description","1","0",""),
("16","Page","can_access_page","page.can_access_page_title","page.can_access_page_description","1","0",""),
("17","Page","can_edit_page","page.can_edit_page_title","page.can_edit_page_description","1","0",""),
("18","Core","can_edit_user","core.can_edit_user_title","core.can_edit_user_description","1","0",""),
("19","Page","can_add_page","page.can_add_page_title","page.can_add_page_description","1","0",""),
("20","Core","can_access_widget_page","core.can_access_widget_page_title","core.can_access_widget_page_description","1","0",""),
("21","Core","can_access_layout_page","core.can_access_layout_page_title","core.can_access_layout_page_description","1","0",""),
("22","Page","can_design_layout","page.can_design_layout_title","page.can_design_layout_description","1","0",""),
("23","Core","can_delete_layout","core.can_delete_layout_title","core.can_delete_layout_description","1","0",""),
("24","Core","can_delete_page","core.can_delete_page_title","core.can_delete_page_description","1","0",""),
("25","Core","can_delete_controller_design","core.can_delete_controller_design_title","core.can_delete_controller_design_description","1","0",""),
("26","Theme","can_edit_controller_layout","theme.can_edit_controller_layout_title","theme.can_edit_controller_layout_description","1","0",""),
("27","Core","can_delete_user_group","core.can_delete_user_group_title","core.can_delete_user_group_description","1","0",""),
("28","Core","can_upload_file","core.can_upload_file_title","core.can_upload_file_description","1","0",""),
("29","Core","can_access_all_upload_folder","core.can_access_all_upload_folder_title","core.can_access_all_upload_folder_description","1","0",""),
("30","User","can_edit_profile","user.can_edit_profile_title","user.can_edit_profile_description","1","0","");
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#request_token` (
  `token_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `code` varchar(100) NOT NULL,
  `verified_time` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `request_type` varchar(255) NOT NULL DEFAULT 'user_verification',
  `params` text,
  PRIMARY KEY (`token_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#seo_data` (
  `seo_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL DEFAULT '0',
  `item_type` varchar(50) NOT NULL DEFAULT 'page',
  `title_tag` varchar(255) NOT NULL,
  `description_tag` varchar(500) NOT NULL,
  `keyword_tag` varchar(500) NOT NULL,
  PRIMARY KEY (`seo_id`),
  KEY `item_id` (`item_id`,`item_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#setting` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(55) NOT NULL DEFAULT 'core',
  `var_name` varchar(255) NOT NULL,
  `setting_type` varchar(255) NOT NULL DEFAULT 'textbox',
  `default_value` text NOT NULL,
  `real_value` text,
  `setting_title` varchar(255) DEFAULT NULL,
  `description` text,
  `updated_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`setting_id`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
INSERT INTO `#_#####_#setting` VALUES("1","core","current_language","textbox","en","en","core.current_language","core.current_language_descripition","0"),
("2","core","default_description","textbox","Simple CMS System","Simple CMS System","core.default_title","core.default_description","0"),
("8","core","default_site_description","textarea","Simple CMS for your business","Simple cms for your business","core.default_site_title_description","core.default_site_description","0"),
("9","core","cache_hook","boolean","0","1","core.cache_hook_title","core.cache_hook_description","0"),
("10","core","disable_ie_warning","boolean","0","0","core.disable_ie_warning_title","core.disable_ie_warning_description","0"),
("11","core","copy_right","textbox","0","LegoPHP","core.copy_right_title","core.copy_right_description","0"),
("12","core","default_site_keywords","textarea","0","LegoPHP","core.default_site_keywords_title","core.default_site_keywords_description","0"),
("13","core","site_delimiter_title","textbox","0","|","core.site_delimiter_title","core.site_delimiter_description","0"),
("14","core","global_time_stamp","textbox","F j, Y","d/m/Y H:i:s","core.global_time_stamp_title","core.global_time_stamp_description","0"),
("15","mail","enable_mail_system","boolean","true","1","mail.enable_mail_system_title","mail.enable_mail_system_description","0"),
("16","mail","send_mail_method","textbox","mail","mail","mail.send_mail_method_title","mail.send_mail_method_description","0"),
("17","mail","from_name","textbox","","LegoPHP","mail.from_name_title","mail.from_name_description","0"),
("18","mail","default_sent_out_email","textbox","no-reply@mail.com","","mail.default_sent_out_email_title","mail.default_sent_out_email_description","0"),
("19","mail","smtp_host","textbox","","legophp.com","mail.smtp_host_title","mail.smtp_host_description","0"),
("20","mail","smtp_user","textbox","","","mail.smtp_user_title","mail.smtp_user_description","0"),
("21","mail","smtp_password","textbox","","","mail.smtp_password_title","mail.smtp_password_description","0"),
("22","mail","smtp_port","textbox","","587","mail.smtp_port_title","mail.smtp_port_description","0"),
("23","mail","smtp_authentication","boolean","0","1","mail.smtp_authentication_title","mail.smtp_authentication_description","0"),
("24","core","site_name","textbox","SimpleCMS","LegoPHP","core.site_name_title","core.site_name_description","0"),
("25","core","support_upload_file_type","textarea","flv,mp3,png,zip,rar,jpg,gif,docx,doc,xls,xlsx,ppt,pptx,bmp,tiff,pdf,mp4","flv,mp3,png,zip,rar,jpg,gif,docx,doc,xls,xlsx,ppt,pptx,bmp,tiff,pdf,mp4","core.supported_upload_file_types","core.supported_upload_file_types_description","0"),
("27","core","default_time_zone","textbox","Asia/Ho_Chi_Minh","Asia/Singapore","core.default_time_zone","core.default_time_zone_description","0"),
("28","core","paypal_email_account","textbox","","lorem@payment.com","core.paypal_email_account","core.paypal_email_account_description","0"),
("29","mail","default_receive_support_email","textbox","","","core.default_receive_support_email","core.default_receive_support_email_description","0"),
("30","core","payment_mode","textbox","sandbox","sandbox","core.payment_mode","core.payment_mode_description","0"),
("31","core","payment_default_gateway","textbox","paypal","paypal","core.payment_default_gateway_title","core.payment_default_gateway_description","0"),
("33","mail","signature_footer","textarea","<span style=\"margin-bottom:15px; margin-top:15px; display:inline-block; font-weight:bold; font-style:italic;\">Kind Regards </span><br>
        <span style=\"margin-bottom:15px; display:inline-block; font-weight:bold; font-style:italic;\">FWS</span>","Kind Regards","mail.signature_footer","mail.signature_footer_description","0"),
("34","core","enable_recaptcha","boolean","0","1","core.enable_recaptcha","core.enable_recaptcha_description","0"),
("35","core","recaptcha_public_key","textbox","","","core.recaptcha_public_key","core.recaptcha_public_key_description","0"),
("36","core","recaptcha_private_key","textbox","","","core.recaptcha_private_key","core.recaptcha_private_key_description","0"),
("42","sitemap","sitemap_caching_time","textbox","1","1","core.sitemap_caching_time_title","core.sitemap_caching_time_description","0"),
("43","sitemap","number_of_url_per_file","textbox","1000","","core.number_of_url_per_file_title","core.number_of_url_per_file_description","0"),
("44","core","ttl_cache","textbox","1000","","core.ttl_cache_title","core.ttl_cache_description","0"),
("45","core","cache_html_output","boolean","0","1","core.cache_html_output_title","core.cache_html_output_description","0");
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#system_stats` (
  `stats_id` int(11) NOT NULL AUTO_INCREMENT,
  `stats_type` varchar(255) NOT NULL,
  `stats_value` int(11) NOT NULL,
  `module_name` varchar(50) NOT NULL DEFAULT 'core',
  `last_update` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`stats_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#theme` (
  `theme_id` int(11) NOT NULL AUTO_INCREMENT,
  `folder` varchar(50) NOT NULL,
  `theme_title` varchar(255) NOT NULL,
  `theme_version` varchar(30) DEFAULT 'N/A',
  `theme_owner` varchar(255) DEFAULT NULL,
  `is_active` tinyint(2) NOT NULL DEFAULT '1',
  `is_default` tinyint(2) NOT NULL DEFAULT '0',
  `params` text,
  `logo` varchar(255) DEFAULT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'frontend',
  PRIMARY KEY (`theme_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
INSERT INTO `#_#####_#theme` VALUES("1","Default","Default Theme SimpleApp","1.0","System","1","1","","Image/Logo/favicon-96x96.png","frontend");
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `user_name` varchar(55) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `user_title` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `hash` varchar(55) NOT NULL,
  `birthday` varchar(55) NOT NULL,
  `joined_day` int(11) DEFAULT NULL,
  `lasted_login` int(11) NOT NULL,
  `main_group_id` int(11) NOT NULL DEFAULT '2',
  `user_text` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `user_image` varchar(255) NOT NULL,
  `address` text,
  `extra_permission` text,
  `session_login` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=77 DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#user_configuration` (
  `user_config_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_value` text,
  PRIMARY KEY (`user_config_id`),
  KEY `fk_vms_configuration_has_vms_user_vms_user1_idx` (`user_id`),
  KEY `fk_vms_configuration_has_vms_user_vms_configuration1_idx` (`config_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#user_group` (
  `user_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) NOT NULL,
  `parent_group_id` int(11) NOT NULL DEFAULT '0',
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `total_member` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#user_group_permission` (
  `group_permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_group_id` int(11) NOT NULL DEFAULT '0',
  `permission_id` int(11) NOT NULL DEFAULT '0',
  `gp_value` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_permission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
CREATE TABLE IF NOT EXISTS `#_#####_#widgets` (
  `widget_id` int(11) NOT NULL AUTO_INCREMENT,
  `widget_name` varchar(255) NOT NULL,
  `widget_router` varchar(255) NOT NULL,
  `module_name` varchar(255) NOT NULL,
  `params` text,
  `can_remove` int(3) NOT NULL DEFAULT '1',
  `params_template` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`widget_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
---------------------------------------

---------------------------------------
INSERT INTO `#_#####_#widgets` VALUES("3","page.widget_html","page/html","page","{\"title\":{\"type\":\"text\",\"value\":\"\",\"title\":\"core.title\"},\"html_content\":{\"type\":\"editor\",\"value\":\"\",\"title\":\"core.content\"}}","0",""),
("4","page.page_content","page/content","page","","0",""),
("5","core.slider","core/slider","core","{\"title\":{\"type\":\"text\",\"value\":\"\",\"title\":\"core.title\"},\"images\":{\"type\":\"slider\",\"value\":\"\",\"title\":\"core.slider\"}}","0","slider"),
("6","Search Filter","core/searchFilter","core","{\"title\":{\"type\":\"text\",\"value\":\"\",\"title\":\"core.title\"}}","1","");
---------------------------------------
