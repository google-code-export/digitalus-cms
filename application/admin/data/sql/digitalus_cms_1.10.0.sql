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
-- Table `users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `name` VARCHAR(30) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(32) NOT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 0,
  `first_name` VARCHAR(50) NULL DEFAULT '',
  `last_name` VARCHAR(50) NULL DEFAULT '',
  `openid` VARCHAR(100) DEFAULT NULL,
  `role` ENUM('guest', 'admin', 'superadmin') NULL DEFAULT 'guest',
  `acl_resources` TEXT NULL,
  PRIMARY KEY (`name`),
  UNIQUE KEY (`openid`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

-- -----------------------------------------------------
-- Table `user_bookmarks`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_bookmarks`;
CREATE TABLE IF NOT EXISTS `user_bookmarks` (
  `id` INT(11) NOT NULL auto_increment,
  `user_name` VARCHAR(30) NOT NULL,
  `url` VARCHAR(100) NOT NULL,
  `label` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_user_bookmarks` FOREIGN KEY (`user_name`) REFERENCES `users`(`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8;
CREATE INDEX `fk_user_bookmarks` ON `user_bookmarks`(`user_name` ASC);

-- -----------------------------------------------------
-- Table `user_notes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_notes`;
CREATE TABLE IF NOT EXISTS `user_notes` (
  `id` INT(11) NOT NULL auto_increment,
  `user_name` VARCHAR(30) NOT NULL,
  `content` TEXT,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_user_notes` FOREIGN KEY (`user_name`) REFERENCES `users`(`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8;
CREATE INDEX `fk_user_notes` ON `user_notes`(`user_name` ASC);

-- -----------------------------------------------------
-- Table `pages`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `parent_id` INT(11) NULL DEFAULT 0,
  `user_name` VARCHAR(30) NULL,
  `create_date` INT(11) DEFAULT NULL,
  `publish_date` INT(11) DEFAULT NULL,
  `archive_date` INT(11) DEFAULT NULL,
  `last_update` INT(11) DEFAULT NULL,
  `publish_level` ENUM('1', '11', '21') DEFAULT '11',
  `name`  VARCHAR(250) DEFAULT NULL,
  `label` VARCHAR(250) DEFAULT NULL,
  `namespace` VARCHAR(100) DEFAULT NULL,
  `content_template` VARCHAR(100) DEFAULT NULL,
  `position` INT(11) DEFAULT NULL,
  `show_on_menu` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (`id`),
--  CONSTRAINT `fk_parent_page` FOREIGN KEY (`parent_id`) REFERENCES `pages`(`id`)   ON DELETE CASCADE   ON UPDATE CASCADE,
  CONSTRAINT `fk_page_author` FOREIGN KEY (`user_name`) REFERENCES `users`(`name`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8;
CREATE INDEX `fk_parent_page` ON `pages`(`parent_id` ASC);
CREATE INDEX `fk_page_author` ON `pages`(`user_name` ASC);

-- -----------------------------------------------------
-- Table `page_nodes`
-- -----------------------------------------------------

DROP TABLE IF EXISTS `page_nodes`;
CREATE TABLE IF NOT EXISTS `page_nodes` (
  `page_id` INT(11) NOT NULL,
  `node_type` VARCHAR(100) NOT NULL DEFAULT 'content',
  `language` ENUM('en', 'de', 'es', 'fr', 'hu', 'it', 'pl', 'ru', 'se') NOT NULL DEFAULT 'en',
  `label` VARCHAR(100) NULL,
  `headline` VARCHAR(100) NULL,
  `content` MEDIUMTEXT NOT NULL DEFAULT '',
  PRIMARY KEY (`page_id`, `node_type`, `language`),
  CONSTRAINT `fk_page_nodes` FOREIGN KEY (`page_id`) REFERENCES `pages`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8;
CREATE INDEX `fk_page_nodes` ON `page_nodes`(`page_id` ASC);

-- ----------------------------
-- Table `data`
-- ----------------------------
DROP TABLE IF EXISTS `data`;
CREATE TABLE `data` (
  `id` INT(11) NOT NULL auto_increment,
  `tags` VARCHAR(500) DEFAULT NULL,
  `data` TEXT,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

-- ----------------------------
-- Records
-- ----------------------------
INSERT INTO `data`
    (`tags`, `data`)
VALUES
    ('site_settings', '<?xml version="1.0"?>\n<settings><name>Digitalus CMS Site</name><online>1</online><addMenuLinks>0</addMenuLinks><default_locale/><default_language>en</default_language><default_charset>utf-8</default_charset><default_timezone>America/Los_Angeles</default_timezone><default_date_format/><default_currency_format/><default_email/><default_email_sender/><use_smtp_mail>0</use_smtp_mail><smtp_host/><smtp_username/><smtp_password/><google_tracking/><google_verify/><title_separator> - </title_separator><add_menu_links>1</add_menu_links><publish_pages>11</publish_pages><doc_type>XHTML1_TRANSITIONAL</doc_type><home_page>1</home_page><page_not_found>2</page_not_found><offline_page>3</offline_page><meta_description/><meta_keywords/><xml_declaration/></settings>');

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
    (1, 0, 'administrator', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 1, '404 Page',     '404 Page',     'content', 'default_default', 0, 0),
    (2, 0, 'administrator', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 1, 'Site Offline', 'Site Offline', 'content', 'default_default', 1, 0),
    (3, 0, 'administrator', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 1, 'Home',         'Home',         'content', 'default_default', 2, 1);

INSERT INTO `page_nodes`
    (`page_id`, `node_type`, `language`, `label`, `headline`, `content`)
VALUES
    (1, 'content',    'en', 'Home',         'Digitalus CMS',          "Congratulations! You have successfully installed Digitalus CMS.<br />To get started why don't you log in and change this page:<br /><ol><li>Log in to site administration with the username and password you set up in the installer.</li><li>Go to the pages section.</li><li>Click on the Home page on the left sidebar.</li><li>Now update it and click update page!</li></ol>If you have any questions here are some helpful links:<br /><ul><li><a href=\"http://forum.digitaluscms.com\">Digitalus Forum</a></li><li><a href=\"http://wiki.digitaluscms.com\">Digitalus Documentation</a><br /></li></ul>"),
    (2, 'errorsite',  'en', '404 Page',     'HTTP/1.1 404 Not Found', "Sorry, the page you are looking for has moved or been renamed."),
    (3, 'errorsite',  'en', 'Site Offline', 'Site Offline',           "Sorry, our site is currently offline for maintenance.");