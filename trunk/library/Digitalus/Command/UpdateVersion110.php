<?php
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
 * @package     Digitalus_Core_Library
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */

/**
 * @see Digitalus_Command_Abstract
 */

/**
 * Update v1.10 helper
 *
 * @author      Lowtower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */
class Digitalus_Command_UpdateVersion110 extends Digitalus_Command_Abstract
{
    /**
     * db adapter
     *
     * @var zend_db_table adapter
     */
    private $_db;

    /**
     * load the db adapter
     *
     */
    public function __construct()
    {
        $this->_db = Zend_Db_Table::getDefaultAdapter();
        parent::__construct();
    }

    /**
     *
     * create pages and content nodes tables
     * validate that there are pages in the content table.
     * migrate content table rows to pages / content_nodes
     *
     */
    public function run($params = NULL)
    {
        $result = $this->_updateTemplateReferences();
        if (!$result) {
            $this->log($this->view->getTranslation('ERROR: could not update database.'));
        } else {
            $this->log($this->view->getTranslation('Databases successfully updated!'));
        }
        unset($result);
        $result = $this->_updateConfig();
        if (!$result) {
            $this->log($this->view->getTranslation('ERROR: could not update config.'));
        } else {
            $this->log($this->view->getTranslation('Config successfully updated!'));
        }
        $this->_updateAuth();
    }

    /**
     * returns details about the current command
     *
     */
    public function info()
    {
        $this->log($this->view->getTranslation('The Update Version 110 command will update your database from version 1.9 to 1.10'));
        $this->log($this->view->getTranslation('Significant changes have been made to the database structure.'));
        $this->log($this->view->getTranslation('Please be sure to make a backup copy of Your databases BEFORE updating!'));
        $this->log($this->view->getTranslation('Params: none'));
    }

    private function _updateTemplateReferences()
    {
        try {
            $db_data          = Digitalus_Db_Table::getTableName('data');
            $db_error_log     = Digitalus_Db_Table::getTableName('error_log');
            $db_traffic_log   = Digitalus_Db_Table::getTableName('traffic_log');
            $db_users         = Digitalus_Db_Table::getTableName('users');
            $db_user_nodes    = Digitalus_Db_Table::getTableName('user_nodes');
            $db_pages         = Digitalus_Db_Table::getTableName('pages');
            $db_page_nodes    = Digitalus_Db_Table::getTableName('page_nodes');
            $db_content_nodes = Digitalus_Db_Table::getTableName('content_nodes');

            /** ************************************************************ **/

            /* *****************************************************************
             * changes to table `traffic_log`
             **************************************************************** */
            $this->_db->query("ALTER TABLE `$db_traffic_log` ENGINE=InnoDB DEFAULT CHARSET=utf8");

            /* *****************************************************************
             * changes to table `users`
             **************************************************************** */
            // change table type to InnoDB
            $this->_db->query("ALTER TABLE `$db_users` ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            // remove auto_increment
            $this->_db->query("ALTER TABLE `$db_users` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL;";
            // Change type of field email
            $this->_db->query("ALTER TABLE `$db_users`
                CHANGE `email` `email` VARCHAR(100) NOT NULL;";
            // Change type of field password
            $this->_db->query("ALTER TABLE `$db_users`
                CHANGE `password` `password` VARCHAR(32) NOT NULL;";
            // Change type of field first_name
            $this->_db->query("ALTER TABLE `$db_users`
                CHANGE `first_name` `first_name` VARCHAR(50) DEFAULT '';";
            // Change type of field last_name
            $this->_db->query("ALTER TABLE `$db_users`
                CHANGE `last_name` `last_name` VARCHAR(50) DEFAULT '';";
            // set field `openid` NULL by DEFAULT and set NULL where the openid is empty
            $this->_db->query("ALTER TABLE `$db_users`
                CHANGE `openid` `openid` VARCHAR(100) DEFAULT NULL;";
            // set 'guest' as DEFAULT instead of 'admin'
            $this->_db->query("ALTER TABLE `$db_users`
                CHANGE `role` `role` ENUM('guest', 'admin', 'superadmin') DEFAULT 'guest';";
            // insert field 'name', `active`
            $this->_db->query("ALTER TABLE `$db_users`
                ADD `name` VARCHAR(30) NOT NULL AFTER `id`,
                ADD `active` TINYINT(1) NOT NULL DEFAULT 0 AFTER `password`;";
            // set fields `openid` unique -> use Validators 'UsernameExists' and 'OpenIdExists' before updating database
            $this->_db->query("ALTER TABLE `$db_users`
                ADD UNIQUE (`openid`);";

            /* *****************************************************************
             * changes to table `user_nodes`
             **************************************************************** */
            // create table `$db_user_nodes`
            $this->_db->query("DROP TABLE IF EXISTS `$db_user_nodes`;";
            $this->_db->query("CREATE TABLE IF NOT EXISTS `$db_user_nodes` (
                `id` INT(11) NOT NULL auto_increment,
                `user_name` VARCHAR(30) NOT NULL,
                `node_type` ENUM('bookmark', 'note') NOT NULL DEFAULT 'bookmark',
                `content` text,
                PRIMARY KEY (`id`)
            ) ENGINE = InnoDB DEFAULT CHARSET = utf8;";
            $this->_db->query("CREATE INDEX `fk_user_nodes` ON `$db_user_nodes`(`user_name` ASC);";

            /* *****************************************************************
             * changes to table `pages`
             **************************************************************** */
            // change type of pages.publish_level, pages.is_home_page and pages.show_on_menu to ENUM/TINYINT
            $this->_db->query("ALTER TABLE `$db_pages`
                CHANGE `parent_id` `parent_id` INT(11) NULL DEFAULT 0,
                CHANGE `publish_level` `publish_level` ENUM('1', '11', '21') DEFAULT '11',
                CHANGE `is_home_page`  `is_home_page`  TINYINT(1) DEFAULT 0,
                CHANGE `show_on_menu`  `show_on_menu`  TINYINT(1) DEFAULT 0;";
            // create new column `user_name`
            $this->_db->query("ALTER TABLE `$db_pages`
                ADD `user_name` VARCHAR(30) NULL AFTER `id`;";

            /* *****************************************************************
             * changes to table `content_nodes`
             **************************************************************** */
            // rename table to `page_nodes`
            $this->_db->query("RENAME TABLE `$db_content_nodes`
                TO `$db_page_nodes`;";
            // remove auto_increment
            $this->_db->query("ALTER TABLE `$db_page_nodes`
                CHANGE `id` `id` INT(11) NOT NULL;";
            // change type of content_nodes.content to 'MEDIUMTEXT'
            $this->_db->query("ALTER TABLE `$db_page_nodes`
                CHANGE `content` `content` MEDIUMTEXT NOT NULL DEFAULT '';";
            // change name and type of field 'version'
            $this->_db->query("ALTER TABLE `$db_page_nodes`
                CHANGE `version` `language` ENUM('en', 'de', 'es', 'fr', 'hu', 'it', 'pl', 'ru', 'se') NOT NULL DEFAULT 'en';";
            // rename field 'node' to 'node_type'
            $this->_db->query("ALTER TABLE `$db_page_nodes`
                CHANGE `node` `node_type` VARCHAR(100) NOT NULL DEFAULT 'content';";
            // insert field 'page_id'
            $this->_db->query("ALTER TABLE `$db_page_nodes`
                ADD `page_id` INT(11) NOT NULL AFTER `parent_id`,
                ADD `label` VARCHAR(100) NULL AFTER `language`,
                ADD `headline` VARCHAR(100) NULL AFTER `label`;";

            /* *****************************************************************
             * INDEXES, FOREIGN KEYS
             **************************************************************** */
            // drop and (re-)create primary keys
            $this->_db->query("ALTER TABLE `$db_users`
                DROP PRIMARY KEY;
                ALTER TABLE `$db_users`
                ADD PRIMARY KEY (`name`);";
            // add indexes on `parent_id` and `user_name`
            $this->_db->query("ALTER TABLE `$db_pages`
                ADD INDEX `fk_parent_page` (`parent_id` ASC),
                ADD INDEX `fk_page_author` (`user_name` ASC),
                ADD CONSTRAINT `fk_page_author` FOREIGN KEY (`user_name`) REFERENCES `$db_users`(`name`) ON DELETE NO ACTION ON UPDATE CASCADE;";
            // add index to be able to set a foreign key later
            $this->_db->query("ALTER TABLE `$db_page_nodes`
                DROP INDEX `NODE_TO_PAGE`,
                DROP INDEX `NODE_KEYS`,
                DROP PRIMARY KEY,
                ADD INDEX `fk_page_nodes` (`page_id` ASC);";

            /* *****************************************************************
             * RECORDS
             **************************************************************** */
            // copy 'email' to 'name' and give user(1) the name 'administrator'
            $this->_db->query("UPDATE `$db_users`
                SET `name` = `email`;";
            $this->_db->query("UPDATE `$db_users`
                SET `name` = 'administrator'
                WHERE `id` = 1 AND `role` = 'superadmin';";
            // set all existing users active
            $this->_db->query("UPDATE `$db_users`
                SET `active` = 1;";
            // set openid NULL instead of empty string
            $this->_db->query("UPDATE  `$db_users`
                SET `openid` = NULL
                WHERE `openid` = '';";
            // fill the new table
            $this->_db->query("INSERT INTO `$db_user_nodes`
                    (`user_name`, `node_type`, `content`)
                SELECT
                    SUBSTRING_INDEX(`parent_id`, '_', -1), `node_type`, `content`
                FROM
                    `$db_page_nodes`
                WHERE
                    SUBSTRING_INDEX(`$db_page_nodes`.`parent_id`, '_', 1) = 'user';";
            // fill column `user_name` with corresponding values from table `users`
            $this->_db->query("UPDATE `$db_user_nodes`
                LEFT JOIN
                    (SELECT `id`, `name` FROM `$db_users` GROUP BY `id`) AS `$db_users` ON `$db_user_nodes`.`user_name` = `$db_users`.`id`
                SET
                    `$db_user_nodes`.`user_name` = `$db_users`.`name`;";
            // set 'author_id' = 1 to verify each 'pages' entry has a valid 'author_id'
            $this->_db->query("UPDATE `$db_pages`
                SET `author_id` = 1 WHERE `author_id` = 0;";
            // fill new column `user_name` with corresponding values from table `$db_users`
            $this->_db->query("UPDATE `$db_pages`
                LEFT JOIN
                    (SELECT `id`, `name` FROM `$db_users` GROUP BY `id`) AS `$db_users` ON `$db_pages`.`author_id` = `$db_users`.`id`
                SET
                    `$db_pages`.`user_name` = `$db_users`.`name`;";
            // drop the rows where parent is 'user'
            $this->_db->query("DELETE FROM `$db_page_nodes`
                WHERE
                    SUBSTRING_INDEX(`parent_id`, '_', 1) = 'user';";
            // add page ids
            $this->_db->query("UPDATE `$db_page_nodes`
                SET
                    `page_id` = SUBSTRING_INDEX(`parent_id`, '_', -1)
                WHERE
                    SUBSTRING_INDEX(`parent_id`, '_', 1) = 'page';";
            // add page labels and headline by making a copy first
            $this->_db->query("CREATE TABLE IF NOT EXISTS `tmp` (
                `id` INT(11) NOT NULL,
                `parent_id` VARCHAR(50) DEFAULT NULL,
                `page_id` INT(11) NOT NULL,
                `node_type` VARCHAR(100) NOT NULL DEFAULT 'content',
                `language` ENUM('en', 'de', 'es', 'fr', 'hu', 'it', 'pl', 'ru', 'se') NOT NULL DEFAULT 'en',
                `label` VARCHAR(100) DEFAULT NULL,
                `headline` VARCHAR(100) DEFAULT NULL,
                `content_type` VARCHAR(100) DEFAULT NULL,
                `content` MEDIUMTEXT NOT NULL
            ) ENGINE = InnoDB DEFAULT CHARSET = utf8;";
            $this->_db->query("INSERT INTO `tmp`
                SELECT *
                FROM `$db_page_nodes`;";
            // updating `page_nodes` with labels
            $this->_db->query("UPDATE `$db_page_nodes`,
                (SELECT `page_id`, `language`, `content` FROM `tmp` WHERE `node_type` = 'label' GROUP BY `page_id`) AS `labels`
                SET
                    `$db_page_nodes`.`label` = `labels`.`content`
                WHERE
                    `$db_page_nodes`.`page_id` = `labels`.`page_id`
                AND
                    `$db_page_nodes`.`language` = `labels`.`language`
                AND
                    `$db_page_nodes`.`node_type` = 'content';";
            // updating `page_nodes` with headlines
            $this->_db->query("UPDATE `$db_page_nodes`,
                (SELECT `page_id`, `language`, `content` FROM `tmp` WHERE `node_type` = 'headline' GROUP BY `page_id`) AS `headlines`
                SET
                    `$db_page_nodes`.`headline`  = `headlines`.`content`
                WHERE
                    `$db_page_nodes`.`page_id`   = `headlines`.`page_id`
                AND
                    `$db_page_nodes`.`language`  = `headlines`.`language`
                AND
                    `$db_page_nodes`.`node_type` = 'content';";

            /* *****************************************************************
             * drop unneeded tables, columns, and rows
             **************************************************************** */
            // remove temporary table `tmp`
            $this->_db->query("DROP TABLE `tmp`;";
            // drop column `pages`.`author_id`, `related_pages` and `design`
            $this->_db->query("ALTER TABLE `$db_pages`
                DROP `author_id`,
                DROP `related_pages`,
                DROP `is_home_page`,
                DROP `design`;";
            // drop column `page_nodes`.`content_type` and `parent_id`
            $this->_db->query("ALTER TABLE `$db_page_nodes`
                DROP `id`,
                DROP `content_type`,
                DROP `parent_id`;";
            // drop column `users`.`id`
            $this->_db->query("ALTER TABLE `$db_users`
                DROP `id`;";
            // remove entries with `node_type` = 'headline' or 'label'
            $this->_db->query("DELETE FROM `$db_page_nodes`
                WHERE
                    `node_type` = 'headline'
                OR
                    `node_type` = 'label'
                OR
                    `content` = '';";

            /* *****************************************************************
             * INDEXES, FOREIGN KEYS
             **************************************************************** */
            // add foreign key
            $this->_db->query("ALTER TABLE `$db_user_nodes`
                ADD CONSTRAINT `fk_user_nodes` FOREIGN KEY (`user_name`) REFERENCES `$db_users`(`name`) ON DELETE CASCADE ON UPDATE CASCADE;";
            // add index to be able to set a foreign key later
            $this->_db->query("ALTER TABLE `$db_page_nodes`
                ADD PRIMARY KEY (`page_id`, `node_type`, `language`),
                ADD CONSTRAINT `fk_page_nodes` FOREIGN KEY (`page_id`) REFERENCES `$db_pages`(`id`) ON DELETE NO ACTION ON UPDATE CASCADE;";

            /* *****************************************************************
             * OPTIMIZE
             **************************************************************** */
            $this->_db->query("OPTIMIZE TABLE
                `$db_data` , `$db_error_log` , `$db_pages` , `$db_page_nodes` , `$db_traffic_log` , `$db_users` , `$db_user_nodes`;";

            /** ************************************************************ **/

            return true;
        } catch (Zend_Db_Statement_Exception $e) {
            $this->log($e);
        }
    }

    private function _updateConfig()
    {
        try {
            $config = new Digitalus_Installer_Config();
            $config->loadFile();
            $data = $config->get();
            $data->production->language->path  = './application/admin/data/languages';
            $data->production->filepath->icons = 'images/icons/silk';
            $config->set($data);
            if ($config->save()) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            $this->log($e);
        }
    }

    private function _updateAuth()
    {
        try {
//TODO
            $user = Digitalus_Auth::getIdentity();
            $user->name = $user->email;
        } catch (Exception $e) {
            $this->log($e);
        }
    }
}