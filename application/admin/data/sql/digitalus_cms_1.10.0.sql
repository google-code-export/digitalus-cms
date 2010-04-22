/**
 * Digitalus CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://digitalus-media.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@digitalus-media.com so we can send you a copy immediately.
 *
 * @author      Lowtower - lowtower@gmx.de
 * @category    Digitalus CMS
 * @package     Digitalus_Installation_SQL
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: digitalus_cms_1.10.0.sql 701 2010-03-05 16:23:59Z lowtower@gmx.de $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */

-- -----------------------------------------------------
-- Table `data`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `data`;
CREATE TABLE IF NOT EXISTS `data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tags` varchar(500) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table `groups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `name` varchar(30) NOT NULL,
  `parent` varchar(30) DEFAULT NULL,
  `label` varchar(30) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `acl_resources` text,
  PRIMARY KEY (`name`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table `pages`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(30) DEFAULT NULL,
  `create_date` int(11) DEFAULT NULL,
  `publish_date` int(11) NOT NULL,
  `archive_date` int(11) NOT NULL,
  `last_update` int(11) NOT NULL,
  `publish_level` enum('1','11','21') DEFAULT '11',
  `name` varchar(250) DEFAULT NULL,
  `label` varchar(250) DEFAULT NULL,
  `namespace` varchar(100) DEFAULT NULL,
  `content_template` varchar(100) DEFAULT NULL,
  `parent_id` int(11) DEFAULT '0',
  `position` int(11) DEFAULT NULL,
  `show_on_menu` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_parent_page` (`parent_id`),
  KEY `fk_page_author` (`user_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=76 ;

-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table `page_nodes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `page_nodes`;
CREATE TABLE IF NOT EXISTS `page_nodes` (
  `page_id` int(11) NOT NULL,
  `node_type` varchar(100) NOT NULL DEFAULT 'content',
  `language` enum('en','de','es','fr','hu','it','pl','ru','se') NOT NULL DEFAULT 'en',
  `label` varchar(100) DEFAULT NULL,
  `headline` varchar(100) DEFAULT NULL,
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`page_id`,`node_type`,`language`),
  KEY `fk_page_nodes` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table `traffic_log`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `traffic_log`;
CREATE TABLE IF NOT EXISTS `traffic_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(200) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `user_name` varchar(30) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  `day` int(1) DEFAULT NULL,
  `week` int(2) DEFAULT NULL,
  `month` int(2) DEFAULT NULL,
  `year` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `name` varchar(30) NOT NULL,
  `first_name` varchar(50) DEFAULT '',
  `last_name` varchar(50) DEFAULT '',
  `email` varchar(100) NOT NULL,
  `password` varchar(32) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `role` varchar(30) DEFAULT 'guest',
  `openid` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `fk_user_roles` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table `user_bookmarks`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_bookmarks`;
CREATE TABLE IF NOT EXISTS `user_bookmarks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(30) NOT NULL,
  `label` varchar(50) NOT NULL,
  `url` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_bookmarks` (`user_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table `user_notes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_notes`;
CREATE TABLE IF NOT EXISTS `user_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(30) NOT NULL,
  `content` text,
  PRIMARY KEY (`id`),
  KEY `fk_user_notes` (`user_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- -----------------------------------------------------

-- -----------------------------------------------------
-- Constraints of table `pages`
-- -----------------------------------------------------
ALTER TABLE `pages`
  ADD CONSTRAINT `fk_page_author` FOREIGN KEY (`user_name`) REFERENCES `users` (`name`) ON DELETE NO ACTION ON UPDATE CASCADE;

-- -----------------------------------------------------
-- Constraints of table `users`
-- -----------------------------------------------------
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_roles` FOREIGN KEY (`role`) REFERENCES `groups` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

-- -----------------------------------------------------
-- Constraints of table `user_bookmarks`
-- -----------------------------------------------------
ALTER TABLE `user_bookmarks`
  ADD CONSTRAINT `fk_user_bookmarks` FOREIGN KEY (`user_name`) REFERENCES `users` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------
-- Constraints of table `user_notes`
-- -----------------------------------------------------
ALTER TABLE `user_notes`
  ADD CONSTRAINT `fk_user_notes` FOREIGN KEY (`user_name`) REFERENCES `users` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------

-- -----------------------------------------------------
-- Records
-- -----------------------------------------------------
INSERT INTO `data`
    (`tags`, `data`)
VALUES
    ('site_settings', '<?xml version="1.0"?>\n<settings><name>Digitalus CMS Site</name><online>1</online><addMenuLinks>0</addMenuLinks><default_locale/><admin_language>en</admin_language><default_language>en</default_language><default_charset>utf-8</default_charset><default_timezone>Europe/Berlin</default_timezone><default_date_format/><default_currency_format/><default_email/><default_email_sender/><use_smtp_mail>0</use_smtp_mail><smtp_host/><smtp_username/><smtp_password/><google_tracking/><google_verify/><title_separator> - </title_separator><add_menu_links>1</add_menu_links><publish_pages>11</publish_pages><doc_type>XHTML1_TRANSITIONAL</doc_type><home_page>3</home_page><page_not_found>2</page_not_found><offline_page>1</offline_page><meta_description/><meta_keywords/><xml_declaration/></settings>');

INSERT INTO `groups`
    (`name`, `parent`, `label`)
VALUES
    ('superadmin', NULL,    'Super Administrator'),
    ('admin',      'guest', 'Site Administrator'),
    ('guest',      NULL,    'Guest');

INSERT INTO `users`
    (`name`, `email`, `password`, `active`, `first_name`, `last_name`, `role`)
VALUES
    ('administrator', 'admin@domain.com', '21232f297a57a5a743894a0e4a801fc3', 1, 'Admin', 'istrator', 'superadmin');

INSERT INTO `user_notes`
    (`user_name`, `content`)
VALUES
    ('administrator', 'You have no notes to view');

INSERT INTO `pages`
    (`id`, `parent_id`, `user_name`, `create_date`, `publish_date`, `last_update`, `publish_level`, `name`, `label`, `namespace`, `content_template`, `position`, `show_on_menu`)
VALUES
    (1, 0, 'administrator', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 1, 'Site Offline', 'Site Offline', 'content', 'default_default', 1, 0),
    (2, 0, 'administrator', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 1, '404 Page',     '404 Page',     'content', 'default_default', 0, 0),
    (3, 0, 'administrator', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 1, 'Home',         'Home',         'content', 'default_default', 2, 1);

INSERT INTO `page_nodes`
    (`page_id`, `node_type`, `language`, `label`, `headline`, `content`)
VALUES
    (1, 'errorsite',  'en', 'Site Offline', 'Site Offline',           "Sorry, our site is currently offline for maintenance."),
    (2, 'errorsite',  'en', '404 Page',     'HTTP/1.1 404 Not Found', "Sorry, the page you are looking for has moved or been renamed."),
    (3, 'content',    'en', 'Home',         'Digitalus CMS',          "Congratulations! You have successfully installed Digitalus CMS.<br />To get started why don't you log in and change this page:<br /><ol><li>Log in to site administration with the username and password you set up in the installer.</li><li>Go to the pages section.</li><li>Click on the Home page on the left sidebar.</li><li>Now update it and click update page!</li></ol>If you have any questions here are some helpful links:<br /><ul><li><a href=\"http://forum.digitaluscms.com\">Digitalus Forum</a></li><li><a href=\"http://wiki.digitaluscms.com\">Digitalus Documentation</a><br /></li></ul>");
