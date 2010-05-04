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
 * @package     Digitalus_Command_SQL
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */

/*
 *******************************************************************************
 * changes to table `traffic_log`
 *******************************************************************************
 */
-- change type tables to InnoDB
ALTER TABLE `traffic_log` ENGINE=InnoDB;

/*
 *******************************************************************************
 * changes to table `groups`
 *******************************************************************************
 */
-- create table
CREATE TABLE IF NOT EXISTS `groups` (
  `name` varchar(30) NOT NULL,
  `parent` varchar(30) DEFAULT NULL,
  `label` varchar(30) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `acl_resources` text,
  PRIMARY KEY  (`name`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- fill table
INSERT INTO `groups` (`name`, `parent`, `label`, `description`, `acl_resources`) VALUES
('guest',      NULL,    'Guest',               NULL, 'a:3:{s:8:"404_page";s:1:"1";s:12:"site_offline";s:1:"1";s:4:"home";s:1:"1";}'),
('admin',      'guest', 'Site Administrator',  NULL, 'a:3:{s:8:"404_page";s:1:"1";s:12:"site_offline";s:1:"1";s:4:"home";s:1:"1";}'),
('superadmin', NULL,    'Super Administrator', NULL, NULL);

/*
 *******************************************************************************
 * changes to table `users`
 *******************************************************************************
 */
-- change type tables to InnoDB
ALTER TABLE `users` ENGINE=InnoDB;
-- insert field 'name', copy 'email' to 'name' and give user(1) the name 'administrator'
ALTER TABLE `users`
    ADD `name` VARCHAR(30) NULL AFTER `id`;
UPDATE `users`
    SET `name` = `email`;
UPDATE `users`
    SET `name` = 'administrator'
    WHERE `id` = 1 AND `role` = 'superadmin';
-- insert field 'active' and set all existing users active
ALTER TABLE `users`
    ADD `active` TINYINT(1) NOT NULL DEFAULT 0 AFTER `name`;
UPDATE `users`
    SET `active` = 1;
-- set field `openid` NULL by default and set NULL where the openid is empty
ALTER TABLE `users`
    CHANGE `openid` `openid` VARCHAR(100) NULL DEFAULT NULL;
UPDATE  `users`
    SET `openid` = NULL
    WHERE `openid` = '';
-- set 'guest' as default instead of 'admin'
ALTER TABLE `users`
    CHANGE `role` `role` VARCHAR(30) DEFAULT 'guest';
-- change field type of `password`, etc.
ALTER TABLE `users`
    CHANGE `first_name` `first_name` VARCHAR(50) DEFAULT '',
    CHANGE `last_name`  `last_name`  VARCHAR(50) DEFAULT '',
    CHANGE `password`   `password`   VARCHAR(32) NOT NULL;
-- remove 'auto_increment' from `id`
ALTER TABLE `users`
    CHANGE `id` `id` INT(10) NOT NULL;
-- drop and (re-)create primary keys
ALTER TABLE `users`
    DROP PRIMARY KEY;
ALTER TABLE `users`
    ADD KEY  `fk_user_roles` (`role`),
    ADD PRIMARY KEY (`name`);

/*
 *******************************************************************************
 * changes to table `pages`
 *******************************************************************************
 */
-- insert `last_update`
ALTER TABLE `pages`
    ADD `last_update` TIMESTAMP NULL DEFAULT NULL AFTER `archive_date`;
-- change type of pages.publish_level, pages.is_home_page and pages.show_on_menu to ENUM/TINYINT
ALTER TABLE `pages`
    CHANGE `publish_level` `publish_level` ENUM('1', '11', '21') NULL DEFAULT '11',
    CHANGE `show_on_menu`  `show_on_menu`  TINYINT(1) NULL DEFAULT 0,
    CHANGE `parent_id`     `parent_id`     INT(11)         DEFAULT 0;
-- change type to match column 'id' of db 'users'
ALTER TABLE `pages`
   CHANGE `author_id` `author_id` INT(10) NOT NULL;
-- set 'author_id' = 1 to verify each 'pages' entry has a valid 'author_id'
UPDATE `pages`
    SET `author_id` = 1 WHERE `author_id` = 0;
-- create new column `user_name`
ALTER TABLE `pages`
    ADD `user_name` VARCHAR(30) NULL AFTER `id`;
-- fill new column `user_name` with corresponding values from table `users`
UPDATE `pages`
    LEFT JOIN
        (SELECT `id`, `name` FROM `users` GROUP BY `id`) AS `users` ON `pages`.`author_id` = `users`.`id`
    SET
        `pages`.`user_name` = `users`.`name`;
-- add index `user_name'
ALTER TABLE `pages`
    ADD INDEX `fk_parent_page` (`parent_id`),
    ADD INDEX `fk_page_author` (`user_name`);

/*
 *******************************************************************************
 * changes to table `user_bookmarks`
 *******************************************************************************
 */
-- create table `user_bookmarks`
DROP TABLE IF EXISTS `user_bookmarks`;
CREATE TABLE `user_bookmarks` (
    `id` int(11) NOT NULL auto_increment,
    `user_name` varchar(30) NOT NULL,
    `label` varchar(50) NOT NULL,
    `url` varchar(100) NOT NULL,
    PRIMARY KEY  (`id`),
    KEY `fk_user_bookmarks` (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- fill the new table
INSERT INTO `user_bookmarks`
    (`user_name`, `label`, `url`)
SELECT
    `parent_id`, `node`, `content`
FROM
    `content_nodes`
WHERE
    `content_type` = 'bookmark';
-- fill column `user_name` with corresponding values from table `users`
UPDATE `user_bookmarks`
    LEFT JOIN
        (SELECT `id`, `name` FROM `users` GROUP BY `id`) AS `users` ON `user_bookmarks`.`user_name` = `users`.`id`
    SET
        `user_bookmarks`.`user_name` = `users`.`name`;

/*
 *******************************************************************************
 * changes to table `user_notes`
 *******************************************************************************
 */
-- create table `user_notes`
DROP TABLE IF EXISTS `user_notes`;
CREATE TABLE `user_notes` (
    `id` int(11) NOT null auto_increment,
    `user_name` varchar(30) NOT NULL,
    `content` text,
    PRIMARY KEY (`id`),
    KEY `fk_user_notes` (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- fill the new table
INSERT INTO `user_notes`
    (`user_name`, `content`)
SELECT
    SUBSTRING_INDEX(`parent_id`, '_', -1), `content`
FROM
    `content_nodes`
WHERE
    SUBSTRING_INDEX(`content_nodes`.`parent_id`, '_', 1) = 'user';
-- fill column `user_name` with corresponding values from table `users`
UPDATE `user_notes`
    LEFT JOIN
        (SELECT `id`, `name` FROM `users` GROUP BY `id`) AS `users` ON `user_notes`.`user_name` = `users`.`id`
    SET
        `user_notes`.`user_name` = `users`.`name`;

/*
 *******************************************************************************
 * changes to table `content_nodes`
 *******************************************************************************
 */
-- rename table to `page_nodes`
RENAME TABLE `content_nodes`
    TO `page_nodes`;
-- change type of content_nodes.content to 'MEDIUMTEXT'
ALTER TABLE `page_nodes`
    CHANGE `content` `content` MEDIUMTEXT;
-- change type of field 'version'
ALTER TABLE `page_nodes`
    CHANGE `version` `language` ENUM('en','de','es','fr','hu','it','pl','ru','se') NOT NULL DEFAULT 'en';
-- rename field 'node' to 'node_type'
ALTER TABLE `page_nodes`
    CHANGE `node` `node_type` VARCHAR(100) NOT NULL DEFAULT 'content';
-- insert field 'page_id', `label`, `headline`
ALTER TABLE `page_nodes`
    ADD `page_id`  INT(11) NOT NULL AFTER `parent_id`,
    ADD `label`    VARCHAR(100) DEFAULT NULL AFTER `language`,
    ADD `headline` VARCHAR(100) DEFAULT NULL AFTER `label`;
-- add key to be able to set a foreign key later
ALTER TABLE `page_nodes`
    ADD KEY `fk_page_nodes` (`page_id`);
-- drop the rows where parent is 'user'
DELETE FROM `page_nodes`
    WHERE
        SUBSTRING_INDEX(`parent_id`, '_', 1) = 'user';
-- create table for the page ids
UPDATE `page_nodes`
    SET
        `page_id` = SUBSTRING_INDEX(`parent_id`, '_', -1)
    WHERE
        SUBSTRING_INDEX(`parent_id`, '_', 1) = 'page';
-- drop the "old" column `parent_id`
ALTER TABLE `page_nodes`
    DROP `parent_id`;

/*
 *******************************************************************************
 * drop unneeded columns and indexes
 *******************************************************************************
 */
-- drop column `pages`.`author_id`
ALTER TABLE `pages`
    DROP `author_id`,
    DROP `label`,
    DROP `related_pages`,
    DROP `is_home_page`,
    DROP `design`;
;
-- drop column `page_nodes`.`content_type` and index `NODE_KEYS`
ALTER TABLE `page_nodes`
    DROP `id`,
    DROP `content_type`;
ALTER TABLE `page_nodes`
    DROP INDEX `NODE_KEYS`;
-- drop column `users`.`id`
ALTER TABLE `users`
    DROP `id`,
    DROP `acl_resources`;

/*
 *******************************************************************************
 * set foreign keys
 *******************************************************************************
 */
-- add foreign key 'user_role' referencing column 'name' of db 'group'
ALTER TABLE `users`
    ADD CONSTRAINT `fk_user_roles` FOREIGN KEY (`role`) REFERENCES `groups`(`name`) ON DELETE SET NULL ON UPDATE CASCADE;
-- add foreign key 'user_name' referencing column 'name' of db 'users'
ALTER TABLE `user_bookmarks`
    ADD CONSTRAINT `fk_user_bookmarks` FOREIGN KEY (`user_name`) REFERENCES `users`(`name`) ON DELETE CASCADE ON UPDATE CASCADE;
-- add foreign key 'user_name' referencing column 'name' of db 'users'
ALTER TABLE `user_notes`
    ADD CONSTRAINT `fk_user_notes` FOREIGN KEY (`user_name`) REFERENCES `users`(`name`) ON DELETE CASCADE ON UPDATE CASCADE;
-- add foreign key 'user_name' referencing column 'name' of db 'users'
ALTER TABLE `pages`
    ADD CONSTRAINT `fk_page_author` FOREIGN KEY (`user_name`) REFERENCES `users`(`name`) ON DELETE NO ACTION ON UPDATE CASCADE;
-- add foreign key 'page_id' referencing column 'id' of db 'pages'
ALTER TABLE `page_nodes`
    ADD PRIMARY KEY (`page_id`, `node_type`, `language`);

/*******************************************************************************
    S T O P    S T O P    S T O P    S T O P    S T O P    S T O P    S T O P
 ******************************************************************************/
