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
 * @version     $Id: UpdateVersion110.sql 701 2010-03-05 16:23:59Z lowtower@gmx.de $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */

/*
 *******************************************************************************
 * changes to table `traffic_log`
 *******************************************************************************
 */
-- change table type to InnoDB
ALTER TABLE `traffic_log` ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*
 *******************************************************************************
 * changes to table `users`
 *******************************************************************************
 */
-- -----------------------------------------------------------------------------
-- CHANGES
-- -----------------------------------------------------------------------------
-- change table type to InnoDB
ALTER TABLE `users` ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- remove AUTO_INCREMENT
ALTER TABLE `users` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL;
-- Change type of field email
ALTER TABLE `users`
    CHANGE `email` `email` VARCHAR(100) NOT NULL;
-- Change type of field password
ALTER TABLE `users`
    CHANGE `password` `password` VARCHAR(32) NOT NULL;
-- Change type of field first_name
ALTER TABLE `users`
    CHANGE `first_name` `first_name` VARCHAR(50) DEFAULT '';
-- Change type of field last_name
ALTER TABLE `users`
    CHANGE `last_name` `last_name` VARCHAR(50) DEFAULT '';
-- set field `openid` NULL by DEFAULT and set NULL where the openid is empty
ALTER TABLE `users`
    CHANGE `openid` `openid` VARCHAR(100) DEFAULT NULL;
-- set 'guest' as DEFAULT instead of 'admin'
ALTER TABLE `users`
    CHANGE `role` `role` ENUM('guest', 'admin', 'superadmin') DEFAULT 'guest';
-- -----------------------------------------------------------------------------
-- INSERTIONS
-- -----------------------------------------------------------------------------
-- insert field 'name', `active`
ALTER TABLE `users`
    ADD `name` VARCHAR(30) NOT NULL AFTER `id`,
    ADD `active` TINYINT(1) NOT NULL DEFAULT 0 AFTER `password`;
-- set fields `openid` unique -> use Validators 'UsernameExists' and 'OpenIdExists' before updating database
ALTER TABLE `users`
    ADD UNIQUE (`openid`);

/*
 *******************************************************************************
 * changes to table `user_bookmarks`
 *******************************************************************************
 */
-- -----------------------------------------------------------------------------
-- CREATE
-- -----------------------------------------------------------------------------
-- create table `user_bookmarks`
DROP TABLE IF EXISTS `user_bookmarks`;
CREATE TABLE IF NOT EXISTS `user_bookmarks` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_name` varchar(30) NOT NULL,
  `label` varchar(50) NOT NULL,
  `url` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*
 *******************************************************************************
 * changes to table `user_notes`
 *******************************************************************************
 */
-- -----------------------------------------------------------------------------
-- CREATE
-- -----------------------------------------------------------------------------
-- create table `user_notes`
DROP TABLE IF EXISTS `user_notes`;
CREATE TABLE IF NOT EXISTS `user_notes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_name` VARCHAR(30) NOT NULL,
  `content` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE INDEX `fk_user_notes` ON `user_notes`(`user_name` ASC);

/*
 *******************************************************************************
 * changes to table `pages`
 *******************************************************************************
 */
-- -----------------------------------------------------------------------------
-- CHANGES
-- -----------------------------------------------------------------------------
-- change type of pages.publish_level, pages.is_home_page and pages.show_on_menu to ENUM/TINYINT
ALTER TABLE `pages`
    CHANGE `parent_id` `parent_id` INT(11) NULL DEFAULT 0,
    CHANGE `publish_level` `publish_level` ENUM('1', '11', '21') DEFAULT '11',
    CHANGE `show_on_menu`  `show_on_menu`  TINYINT(1) DEFAULT 0;
-- -----------------------------------------------------------------------------
-- INSERTIONS
-- -----------------------------------------------------------------------------
-- create new column `user_name`
ALTER TABLE `pages`
    ADD `user_name` VARCHAR(30) NULL AFTER `id`;

/*
 *******************************************************************************
 * changes to table `content_nodes`
 *******************************************************************************
 */
-- -----------------------------------------------------------------------------
-- CHANGES
-- -----------------------------------------------------------------------------
-- rename table to `page_nodes`
RENAME TABLE `content_nodes`
    TO `page_nodes`;
-- remove AUTO_INCREMENT
ALTER TABLE `page_nodes`
    CHANGE `id` `id` INT(11) NOT NULL;
-- change type of content_nodes.content to 'MEDIUMTEXT'
ALTER TABLE `page_nodes`
    CHANGE `content` `content` MEDIUMTEXT NOT NULL DEFAULT '';
-- change name and type of field 'version'
ALTER TABLE `page_nodes`
    CHANGE `version` `language` ENUM('en', 'de', 'es', 'fr', 'hu', 'it', 'pl', 'ru', 'se') NOT NULL DEFAULT 'en';
-- rename field 'node' to 'node_type'
ALTER TABLE `page_nodes`
    CHANGE `node` `node_type` VARCHAR(100) NOT NULL DEFAULT 'content';
-- -----------------------------------------------------------------------------
-- INSERTIONS
-- -----------------------------------------------------------------------------
-- insert field 'page_id'
ALTER TABLE `page_nodes`
    ADD `page_id` INT(11) NOT NULL AFTER `parent_id`,
    ADD `label` VARCHAR(100) NULL AFTER `language`,
    ADD `headline` VARCHAR(100) NULL AFTER `label`;

/*
 *******************************************************************************
 * INDEXES, FOREIGN KEYS
 *******************************************************************************
 */
-- -----------------------------------------------------------------------------
-- USERS
-- -----------------------------------------------------------------------------
-- drop and (re-)create primary keys
ALTER TABLE `users`
   DROP PRIMARY KEY;
ALTER TABLE `users`
   ADD PRIMARY KEY (`name`);
-- -----------------------------------------------------------------------------
-- PAGES
-- -----------------------------------------------------------------------------
-- add indexes on `parent_id` and `user_name`
ALTER TABLE `pages`
    ADD INDEX `fk_parent_page` (`parent_id` ASC),
    ADD INDEX `fk_page_author` (`user_name` ASC),
    ADD CONSTRAINT `fk_page_author` FOREIGN KEY (`user_name`) REFERENCES `users`(`name`) ON DELETE NO ACTION ON UPDATE CASCADE;
-- -----------------------------------------------------------------------------
-- PAGE_NODES
-- -----------------------------------------------------------------------------
-- add index to be able to set a foreign key later
ALTER TABLE `page_nodes`
    DROP INDEX `NODE_TO_PAGE`,
    DROP INDEX `NODE_KEYS`,
    DROP PRIMARY KEY,
    ADD INDEX `fk_page_nodes` (`page_id` ASC);

/*
 *******************************************************************************
 * RECORDS
 *******************************************************************************
 */
-- -----------------------------------------------------------------------------
-- USERS
-- -----------------------------------------------------------------------------
-- copy 'email' to 'name' and give user(1) the name 'administrator'
UPDATE `users`
    SET `name` = `email`;
UPDATE `users`
    SET `name` = 'administrator'
    WHERE `id` = 1 AND `role` = 'superadmin';
-- set all existing users active
UPDATE `users`
    SET `active` = 1;
-- set openid NULL instead of empty string
UPDATE  `users`
    SET `openid` = NULL
    WHERE `openid` = '';
-- -----------------------------------------------------------------------------
-- USER_BOOKMARKS
-- -----------------------------------------------------------------------------
-- fill the new table
INSERT INTO `user_bookmarks`
    (`user_name`, `label`, `url`)
SELECT
    `parent_id`, `node_type`, `content`
FROM
    `page_nodes`
WHERE
    `page_nodes`.`content_type` = 'bookmark';
-- fill column `user_name` with corresponding values from table `users`
UPDATE `user_bookmarks`
    LEFT JOIN
        (SELECT `id`, `name` FROM `users` GROUP BY `id`) AS `users` ON `user_bookmarks`.`user_name` = `users`.`id`
    SET
        `user_bookmarks`.`user_name` = `users`.`name`;
-- -----------------------------------------------------------------------------
-- USER_NOTES
-- -----------------------------------------------------------------------------
-- fill the new table
INSERT INTO `user_notes`
    (`user_name`, `content`)
SELECT
    SUBSTRING_INDEX(`parent_id`, '_', -1), `content`
FROM
    `page_nodes`
WHERE
    SUBSTRING_INDEX(`page_nodes`.`parent_id`, '_', 1) = 'user'
AND
    `page_nodes`.`node_type` = 'note';
-- fill column `user_name` with corresponding values from table `users`
UPDATE `user_notes`
    LEFT JOIN
        (SELECT `id`, `name` FROM `users` GROUP BY `id`) AS `users` ON `user_notes`.`user_name` = `users`.`id`
    SET
        `user_notes`.`user_name` = `users`.`name`;
-- -----------------------------------------------------------------------------
-- PAGES
-- -----------------------------------------------------------------------------
-- set 'author_id' = 1 to verify each 'pages' entry has a valid 'author_id'
UPDATE `pages`
    SET `author_id` = 1 WHERE `author_id` = 0;
-- fill new column `user_name` with corresponding values from table `users`
UPDATE `pages`
    LEFT JOIN
        (SELECT `id`, `name` FROM `users` GROUP BY `id`) AS `users` ON `pages`.`author_id` = `users`.`id`
    SET
        `pages`.`user_name` = `users`.`name`;
-- -----------------------------------------------------------------------------
-- PAGE_NODES
-- -----------------------------------------------------------------------------
-- drop the rows where parent is 'user'
DELETE FROM `page_nodes`
    WHERE
        SUBSTRING_INDEX(`parent_id`, '_', 1) = 'user';
-- add page ids
UPDATE `page_nodes`
    SET
        `page_id` = SUBSTRING_INDEX(`parent_id`, '_', -1)
    WHERE
        SUBSTRING_INDEX(`parent_id`, '_', 1) = 'page';
-- add page labels and headline by making a copy first
CREATE TABLE IF NOT EXISTS `tmp` (
    `id` INT(11) NOT NULL,
    `parent_id` VARCHAR(50) DEFAULT NULL,
    `page_id` INT(11) NOT NULL,
    `node_type` VARCHAR(100) NOT NULL DEFAULT 'content',
    `language` ENUM('en', 'de', 'es', 'fr', 'hu', 'it', 'pl', 'ru', 'se') NOT NULL DEFAULT 'en',
    `label` VARCHAR(100) DEFAULT NULL,
    `headline` VARCHAR(100) DEFAULT NULL,
    `content_type` VARCHAR(100) DEFAULT NULL,
    `content` MEDIUMTEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `tmp`
    SELECT *
    FROM `page_nodes`;
-- updating `page_nodes` with labels
UPDATE `page_nodes`,
    (SELECT `page_id`, `language`, `content` FROM `tmp` WHERE `node_type` = 'label' GROUP BY `page_id`) AS `labels`
    SET
        `page_nodes`.`label` = `labels`.`content`
    WHERE
        `page_nodes`.`page_id` = `labels`.`page_id`
    AND
        `page_nodes`.`language` = `labels`.`language`
    AND
        `page_nodes`.`node_type` = 'content';
-- updating `page_nodes` with headlines
UPDATE `page_nodes`,
    (SELECT `page_id`, `language`, `content` FROM `tmp` WHERE `node_type` = 'headline' GROUP BY `page_id`) AS `headlines`
    SET
        `page_nodes`.`headline` = `headlines`.`content`
    WHERE
        `page_nodes`.`page_id` = `headlines`.`page_id`
    AND
        `page_nodes`.`language` = `headlines`.`language`
    AND
        `page_nodes`.`node_type` = 'content';

/*
 *******************************************************************************
 * drop unneeded tables, columns, and rows
 *******************************************************************************
 */
-- remove temporary table `tmp`
DROP TABLE `tmp`;
-- drop column `pages`.`author_id`, `related_pages` and `design`
ALTER TABLE `pages`
    DROP `author_id`,
    DROP `related_pages`,
    DROP `is_home_page`,
    DROP `design`;
-- drop column `page_nodes`.`content_type` and `parent_id`
ALTER TABLE `page_nodes`
    DROP `id`,
    DROP `content_type`,
    DROP `parent_id`;
-- drop column `users`.`id`
ALTER TABLE `users`
    DROP `id`;
-- remove entries with `node_type` = 'headline' or 'label'
DELETE FROM `page_nodes`
    WHERE
        `node_type` = 'headline'
    OR
        `node_type` = 'label'
    OR
        `content` = '';

/*
 *******************************************************************************
 * INDEXES, FOREIGN KEYS
 *******************************************************************************
 */
-- -----------------------------------------------------------------------------
-- USER_BOOKMARKS
-- -----------------------------------------------------------------------------
-- add foreign key
ALTER TABLE `user_bookmarks`
    ADD CONSTRAINT `fk_user_bookmarks` FOREIGN KEY (`user_name`) REFERENCES `users`(`name`) ON DELETE CASCADE ON UPDATE CASCADE;
-- -----------------------------------------------------------------------------
-- USER_NOTES
-- -----------------------------------------------------------------------------
-- add foreign key
ALTER TABLE `user_notes`
    ADD CONSTRAINT `fk_user_notes` FOREIGN KEY (`user_name`) REFERENCES `users`(`name`) ON DELETE CASCADE ON UPDATE CASCADE;
-- -----------------------------------------------------------------------------
-- PAGE_NODES
-- -----------------------------------------------------------------------------
-- add index to be able to set a foreign key later
ALTER TABLE `page_nodes`
    ADD PRIMARY KEY (`page_id`, `node_type`, `language`),
    ADD CONSTRAINT `fk_page_nodes` FOREIGN KEY (`page_id`) REFERENCES `pages`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*
 *******************************************************************************
 * OPTIMIZE
 *******************************************************************************
 */
OPTIMIZE TABLE
    `data` , `error_log` , `pages` , `page_nodes` , `traffic_log` , `users` , `user_bookmarks`, `user_notes`;