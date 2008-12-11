/*
MySQL Data Transfer
Source Host: localhost
Source Database: digitalus_cms_1_5
Target Host: localhost
Target Database: digitalus_cms_1_5
Date: 12/11/2008 12:22:35 AM
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for content_nodes
-- ----------------------------
CREATE TABLE `content_nodes` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` varchar(50) default NULL,
  `node` varchar(100) default NULL,
  `version` varchar(100) default NULL,
  `content_type` varchar(100) default NULL,
  `content` text,
  PRIMARY KEY  (`id`),
  KEY `NODE_TO_PAGE` (`parent_id`),
  KEY `NODE_KEYS` (`node`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for data
-- ----------------------------
CREATE TABLE `data` (
  `id` int(11) NOT NULL auto_increment,
  `tags` varchar(500) default NULL,
  `data` text,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for designs
-- ----------------------------
CREATE TABLE `designs` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(250) default NULL,
  `notes` text,
  `layout` varchar(500) default NULL,
  `styles` text,
  `inline_styles` text,
  `template` varchar(500) default NULL,
  `placeholders` text,
  `scripts` text,
  `is_default` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for error_log
-- ----------------------------
CREATE TABLE `error_log` (
  `id` int(11) NOT NULL auto_increment,
  `referer` text character set latin1,
  `uri` text character set latin1,
  `date_time` int(11) default NULL,
  `error_data` text,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pages
-- ----------------------------
CREATE TABLE `pages` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(250) default NULL,
  `label` varchar(250) default NULL,
  `content_template` varchar(100) default NULL,
  `related_pages` text,
  `parent_id` int(11) default NULL,
  `position` int(11) default NULL,
  `is_home_page` int(11) default NULL,
  `show_on_menu` int(11) default NULL,
  `design` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for people
-- ----------------------------
CREATE TABLE `people` (
  `id` int(11) NOT NULL auto_increment,
  `role` varchar(50) default NULL,
  `email` varchar(200) default NULL,
  `password` text,
  `first_name` varchar(100) default NULL,
  `last_name` varchar(100) default NULL,
  `address` varchar(250) default NULL,
  `city` varchar(100) default NULL,
  `state` varchar(100) default NULL,
  `zip` varchar(20) default NULL,
  `country` varchar(100) default NULL,
  `phone` varchar(50) default NULL,
  `phone_alt` varchar(50) default NULL,
  `properties` text,
  `date_added` int(11) default NULL,
  `date_updated` int(11) default NULL,
  `information` text,
  `filepath` varchar(250) default NULL,
  `category` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for redirectors
-- ----------------------------
CREATE TABLE `redirectors` (
  `id` int(11) NOT NULL auto_increment,
  `request` text character set latin1,
  `response` text character set latin1,
  `response_code` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for references
-- ----------------------------
CREATE TABLE `references` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) default NULL,
  `child_id` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `REL` (`parent_id`,`child_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for traffic_log
-- ----------------------------
CREATE TABLE `traffic_log` (
  `id` int(11) NOT NULL auto_increment,
  `page` varchar(200) character set latin1 default NULL,
  `ip` varchar(50) character set latin1 default NULL,
  `user_id` int(2) default NULL,
  `timestamp` int(11) default NULL,
  `day` int(1) default NULL,
  `week` int(2) default NULL,
  `month` int(2) default NULL,
  `year` int(4) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=73142 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users
-- ----------------------------
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `first_name` varchar(45) character set latin1 NOT NULL default '',
  `last_name` varchar(45) character set latin1 NOT NULL default '',
  `email` varchar(100) character set latin1 NOT NULL default '',
  `password` text character set latin1 NOT NULL,
  `role` varchar(45) character set latin1 NOT NULL default 'staff',
  `acl_resources` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `content_nodes` VALUES ('103', 'page_21', 'teaser', null, null, 'test');
INSERT INTO `content_nodes` VALUES ('104', 'page_21', 'content', null, null, 'test');
INSERT INTO `content_nodes` VALUES ('105', 'page_21', 'update', null, null, 'Update Page');
INSERT INTO `content_nodes` VALUES ('106', 'user_1', 'note', null, null, 'You have no notes to view');
INSERT INTO `data` VALUES ('1', 'site_settings', '<?xml version=\"1.0\"?>\n<settings><name>Digitalus 1.5.0 test</name><online>0</online><addMenuLinks>0</addMenuLinks><default_locale/><default_language>en</default_language><default_charset>utf8</default_charset><default_date_format/><default_currency_format/><default_email>info@digitaluscms.com</default_email><default_email_sender>Digitalus CMS</default_email_sender><use_smtp_mail>0</use_smtp_mail><smtp_host/><smtp_username/><smtp_password/><google_tracking/><google_verify/><title_separator> - </title_separator><add_menu_links>1</add_menu_links><doc_type>XHTML1_TRANSITIONAL</doc_type></settings>\n');
INSERT INTO `data` VALUES ('2', 'meta_data_3', '<?xml version=\"1.0\"?>\n<meta_data><page_title>sdgdsf</page_title><filename></filename><meta_description>sdgsd</meta_description><keywords></keywords><search_tags></search_tags><update>Update Meta Data</update><page_id>3</page_id></meta_data>\n');
INSERT INTO `data` VALUES ('3', 'properties_3', '<?xml version=\"1.0\"?>\n<properties><test>value</test><another>value</another></properties>\n');
INSERT INTO `data` VALUES ('4', 'meta_data_1', '<?xml version=\"1.0\"?>\n<meta_data><page_title>sd</page_title><filename></filename><meta_description></meta_description><keywords></keywords><search_tags></search_tags><update>Update Meta Data</update><page_id>1</page_id></meta_data>\n');
INSERT INTO `designs` VALUES ('15', 'Inner', 'This is the standard inner page.', 'site.phtml', 'a:2:{s:10:\"blank-page\";a:2:{i:0;s:7:\"nav.css\";i:1;s:9:\"style.css\";}s:8:\"grid-960\";a:1:{i:0;s:7:\"960.css\";}}', 'body{\r\nbackground:#333;\r\n}', null, null, null, '0');
INSERT INTO `designs` VALUES ('16', 'Home', 'This is the', 'site.phtml', 'a:2:{s:10:\"blank-page\";a:1:{i:0;s:9:\"style.css\";}s:8:\"grid-960\";a:1:{i:0;s:7:\"960.css\";}}', '', null, null, null, '1');
INSERT INTO `designs` VALUES ('17', 'Digitalus Media', '', 'digitalus-media.phtml', 'a:2:{s:15:\"digitalus_media\";a:2:{i:0;s:7:\"nav.css\";i:1;s:9:\"style.css\";}s:8:\"grid-960\";a:1:{i:0;s:7:\"960.css\";}}', '', null, null, null, '0');
INSERT INTO `pages` VALUES ('21', 'Home', null, 'base_page', null, '0', null, '1', '1', null);

