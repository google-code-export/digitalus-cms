/*
MySQL Data Transfer
Source Host: localhost
Source Database: digitalus_dev
Target Host: localhost
Target Database: digitalus_dev
Date: 6/22/2009 10:14:52 AM
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for content_nodes
-- ----------------------------
DROP TABLE IF EXISTS `content_nodes`;
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for data
-- ----------------------------
DROP TABLE IF EXISTS `data`;
CREATE TABLE `data` (
  `id` int(11) NOT NULL auto_increment,
  `tags` varchar(500) default NULL,
  `data` text,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for error_log
-- ----------------------------
DROP TABLE IF EXISTS `error_log`;
CREATE TABLE `error_log` (
  `id` int(11) NOT NULL auto_increment,
  `referer` text,
  `uri` text,
  `date_time` int(11) default NULL,
  `error_data` text,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pages
-- ----------------------------
DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL auto_increment,
  `author_id` int(11) default NULL,
  `create_date` int(11) default NULL,
  `publish_date` int(11) default NULL,
  `archive_date` int(11) default NULL,
  `publish_level` int(11) default NULL,
  `name` varchar(250) default NULL,
  `label` varchar(250) default NULL,
  `namespace` varchar(100) default NULL,
  `content_template` varchar(100) default NULL,
  `related_pages` text,
  `parent_id` int(11) default NULL,
  `position` int(11) default NULL,
  `is_home_page` int(11) default NULL,
  `show_on_menu` int(11) default NULL,
  `design` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for traffic_log
-- ----------------------------
DROP TABLE IF EXISTS `traffic_log`;
CREATE TABLE `traffic_log` (
  `id` int(11) NOT NULL auto_increment,
  `page` varchar(200) default NULL,
  `ip` varchar(50) default NULL,
  `user_id` int(2) default NULL,
  `timestamp` int(11) default NULL,
  `day` int(1) default NULL,
  `week` int(2) default NULL,
  `month` int(2) default NULL,
  `year` int(4) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `first_name` varchar(45) NOT NULL default '',
  `last_name` varchar(45) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `password` text NOT NULL,
  `role` varchar(45) NOT NULL default 'staff',
  `acl_resources` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `content_nodes` VALUES ('1', 'page_1', 'content', null, null, 'Welcome to Digitalus CMS');
INSERT INTO `content_nodes` VALUES ('2', 'page_1', 'tagline', 'en', null, 'About Digitalus');
INSERT INTO `content_nodes` VALUES ('3', 'page_1', 'content', 'en', null, 'Congratulations! You have successfully installed Digitalus CMS.<br>To get started why don\'t you log in and change this page:<br><ol><li>Log in to site administration with the username and password you set up in the installer.</li><li>Go to the pages section.</li><li>Click on the Home page on the left sidebar.</li><li>Now update it and click update page!</li></ol>If you have any questions here are some helpful links:<br><ul><li><a href=\"http://forum.digitaluscms.com\">Digitalus Forum</a></li><li><a href=\"http://wiki.digitaluscms.com\">Digitalus Documentation</a><br></li></ul>');
INSERT INTO `content_nodes` VALUES ('4', 'page_1', 'headline', 'en', null, 'Digitalus CMS');
INSERT INTO `content_nodes` VALUES ('5', 'page_1', 'teaser', 'en', null, '');
INSERT INTO `content_nodes` VALUES ('6', 'page_2', 'headline', 'en', null, 'HTTP/1.1 404 Not Found');
INSERT INTO `content_nodes` VALUES ('7', 'page_2', 'teaser', 'en', null, '');
INSERT INTO `content_nodes` VALUES ('8', 'page_2', 'content', 'en', null, 'Sorry, the page you are looking for has moved or been renamed.');
INSERT INTO `content_nodes` VALUES ('9', 'page_3', 'headline', 'en', null, 'Site Offline');
INSERT INTO `content_nodes` VALUES ('10', 'page_3', 'teaser', 'en', null, '');
INSERT INTO `content_nodes` VALUES ('11', 'page_3', 'content', 'en', null, 'Sorry, our site is currently offline for maintenance.');
INSERT INTO `content_nodes` VALUES ('12', 'user_1', 'note', null, null, 'You have no notes to view');
INSERT INTO `data` VALUES ('1', 'site_settings', '<?xml version=\"1.0\"?>\n<settings><name>Digitalus CMS Site</name><online>1</online><addMenuLinks>0</addMenuLinks><default_locale/><default_language>en</default_language><default_charset>utf-8</default_charset><default_timezone>America/Los_Angeles</default_timezone><default_date_format/><default_currency_format/><default_email/><default_email_sender/><use_smtp_mail>0</use_smtp_mail><smtp_host/><smtp_username/><smtp_password/><google_tracking/><google_verify/><title_separator> - </title_separator><add_menu_links>1</add_menu_links><doc_type>XHTML1_TRANSITIONAL</doc_type><home_page>1</home_page><page_not_found>2</page_not_found><offline_page>3</offline_page><meta_description/><meta_keywords/></settings>\n');
INSERT INTO `pages` VALUES ('1', '1', '1231952304', null, null, null, 'Home', '', 'content', 'default_default', null, '0', '2', '1', '1', null);
INSERT INTO `pages` VALUES ('2', '1', '1234630372', null, null, null, '404 Page', '', 'content', 'default_default', null, '0', '0', null, '0', null);
INSERT INTO `pages` VALUES ('3', '1', '1234630436', null, null, null, 'Site Offline', '', 'content', 'default_default', null, '0', '1', null, '0', null);
INSERT INTO `users` VALUES ('1', 'Admin', 'User', 'admin@email.com', '5f4dcc3b5aa765d61d8327deb882cf99', 'superadmin', null);
