-- ----------------------------
-- Table structure for content_nodes
-- ----------------------------
DROP TABLE IF EXISTS `content_nodes`;
CREATE TABLE `content_nodes` (
    `id` int(11) NOT null auto_increment,
    `parent_id` varchar(50) default null,
    `node` varchar(100) default null,
    `version` varchar(100) default null,
    `content_type` varchar(100) default null,
    `content` text,
    PRIMARY KEY  (`id`),
    KEY `NODE_TO_PAGE` (`parent_id`),
    KEY `NODE_KEYS` (`node`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for data
-- ----------------------------
DROP TABLE IF EXISTS `data`;
CREATE TABLE `data` (
    `id` int(11) NOT null auto_increment,
    `tags` varchar(500) default null,
    `data` text,
    PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for error_log
-- ----------------------------
DROP TABLE IF EXISTS `error_log`;
CREATE TABLE `error_log` (
    `id` int(11) NOT null auto_increment,
    `referer` text,
    `uri` text,
    `date_time` int(11) default null,
    `error_data` text,
    PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pages
-- ----------------------------
DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
    `id` int(11) NOT null auto_increment,
    `author_id` int(11) default null,
    `create_date` int(11) default null,
    `publish_date` int(11) default null,
    `archive_date` int(11) default null,
    `publish_level` int(11) default null,
    `name` varchar(250) default null,
    `label` varchar(250) default null,
    `namespace` varchar(100) default null,
    `content_template` varchar(100) default null,
    `related_pages` text,
    `parent_id` int(11) default null,
    `position` int(11) default null,
    `is_home_page` int(11) default null,
    `show_on_menu` int(11) default null,
    `design` int(11) default null,
    PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for traffic_log
-- ----------------------------
DROP TABLE IF EXISTS `traffic_log`;
CREATE TABLE `traffic_log` (
    `id` int(11) NOT null auto_increment,
    `page` varchar(200) default null,
    `ip` varchar(50) default null,
    `user_id` int(2) default null,
    `timestamp` int(11) default null,
    `day` int(1) default null,
    `week` int(2) default null,
    `month` int(2) default null,
    `year` int(4) default null,
    PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` int(10) unsigned NOT null auto_increment,
    `first_name` varchar(45) NOT null default '',
    `last_name` varchar(45) NOT null default '',
    `email` varchar(100) NOT null default '',
    `openid` varchar(100) NOT null default '',
    `password` text NOT null,
    `role` varchar(45) NOT null default 'admin',
    `acl_resources` text,
    PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records
-- ----------------------------
INSERT INTO `content_nodes`
VALUES
    (1,  'user_1', 'note', null, null, 'You have no notes to view'),
    (2,  'page_1', 'content', 'en', null, 'Welcome to Digitalus CMS'),
    (3,  'page_1', 'tagline', 'en', null, 'About Digitalus'),
    (4,  'page_1', 'content', 'en', null, "Congratulations! You have successfully installed Digitalus CMS.<br>To get started why don't you log in and change this page:<br><ol><li>Log in to site administration with the username and password you set up in the installer.</li><li>Go to the pages section.</li><li>Click on the Home page on the left sidebar.</li><li>Now update it and click update page!</li></ol>If you have any questions here are some helpful links:<br><ul><li><a href=\"http://forum.digitaluscms.com\">Digitalus Forum</a></li><li><a href=\"http://wiki.digitaluscms.com\">Digitalus Documentation</a><br></li></ul>"),
    (5,  'page_1', 'headline', 'en', null, 'Digitalus CMS'),
    (6,  'page_1', 'teaser', 'en', null, ''),
    (7,  'page_2', 'headline', 'en', null, 'HTTP/1.1 404 Not Found'),
    (8,  'page_2', 'teaser', 'en', null, ''),
    (9,  'page_2', 'content', 'en', null, 'Sorry, the page you are looking for has moved or been renamed.'),
    (10, 'page_3', 'headline', 'en', null, 'Site Offline'),
    (11, 'page_3', 'teaser', 'en', null, ''),
    (12, 'page_3', 'content', 'en', null, 'Sorry, our site is currently offline for maintenance.');


INSERT INTO `data`
VALUES
    (1, 'site_settings', '<?xml version="1.0"?>\n<settings><name>Digitalus CMS Site</name><online>1</online><addMenuLinks>0</addMenuLinks><default_locale/><default_language>en</default_language><default_charset>utf-8</default_charset><default_timezone>America/Los_Angeles</default_timezone><default_date_format/><default_currency_format/><default_email/><default_email_sender/><use_smtp_mail>0</use_smtp_mail><smtp_host/><smtp_username/><smtp_password/><google_tracking/><google_verify/><title_separator> - </title_separator><add_menu_links>1</add_menu_links><publish_pages>11</publish_pages><doc_type>XHTML1_TRANSITIONAL</doc_type><home_page>1</home_page><page_not_found>2</page_not_found><offline_page>3</offline_page><meta_description/><meta_keywords/><xml_declaration/></settings>');

INSERT INTO `pages`
VALUES
    (1, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), null, 1, 'Home', '', 'content', 'default_default', null, 0, 2, 1, 1, null),
    (2, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), null, 1, '404 Page', '', 'content', 'default_default', null, 0, 0, null, 0, null),
    (3, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), null, 1, 'Site Offline', '', 'content', 'default_default', null, 0, 1, null, 0, null);

INSERT INTO `users`
VALUES
    (1, 'Admin', 'istrator', 'admin@domain.com', '', '21232f297a57a5a743894a0e4a801fc3', 'superadmin', '');