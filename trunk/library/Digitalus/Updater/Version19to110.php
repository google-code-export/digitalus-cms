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
 * @package     Digitalus
 * @subpackage  Digitalus_Updater
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */

/**
 * @see Digitalus_Updater_Abstract
 */
require_once 'Digitalus/Updater/Abstract.php';

/**
 * Updater v1.10
 *
 * @author      Lowtower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */
class Digitalus_Updater_Version19to110 extends Digitalus_Updater_Abstract
{
    const VERSION_OLD = '1.9';
    const VERSION_NEW = '1.10';

    /**
     * information about the new installation
     *
     * @var array
     */
    protected $_installationInformation = array(
        'With this Version of Digitalus come a lot of changes in the directory and database structure.',
        'Also, a lot of additional validators have been added to the CMS, e.g. for page and user names.',
        'Page names now have to be unique. Thus, updating an existing system is not trivial and might fail!',
        'Some constraints have been added to the database to make the data more consistent. This might also cause the update to fail!',
        'As a new feature user groups have been added to the system. The access control is now done according to user groups.',
        'Additionally, public pages can be restricted to only registered users, respectively users belonging to a special user group.',
        "From this version on Digitalus CMS uses a username for authenticating! The superadministrator's account was given the username 'administrator'",
    );

    public static function getNewVersion()
    {
        return self::VERSION_NEW;
    }

    public static function getOldVersion()
    {
        return self::VERSION_OLD;
    }

    public static function checkVersions($newVersion, $oldVersion)
    {
        if ((string)$newVersion == self::getNewVersion() && (string)$oldVersion == self::getOldVersion()) {
            return true;
        }
        return false;
    }

    public static function getConfigPath($version = 'new')
    {
        $version = strtolower($version);
        switch ($version) {
            case 'old':
                $path = Digitalus_Installer_Config::PATH_TO_CONFIG_OLD;
                break;
            case 'new':
            default:
                $path = Digitalus_Installer_Config::PATH_TO_CONFIG;
                break;
        }
        return $path;
    }

    /**
     *
     * Makes a lot of changes to the database structure
     *
     * @throws Digitalus_Updater_Exception
     * @return void
     */
    public function run()
    {
        $this->_changeData();
        $this->_createGroups();
        $this->_changeUsers();
        $this->_changePages();
        $this->_createUserBookmarks();
        $this->_createUserNotes();
        $this->_changeContentNodes();
        $this->_dropUnneeded();

        // errors might occur while setting foreign keys
        try {
            $this->_setForeignKeys();
        } catch (PDOException $e) {
            throw new Digitalus_Updater_Exception('An error occurred while setting the foreign keys');
        }

        // optimise database
        $this->optimise();

        try {
            $this->_updateConfig();
        } catch (Exception $e) {
            throw new Digitalus_Updater_Exception("The config file coudn't be updated");
        }

        // remove directories
        $this->_removeDirectories();
    }

    /**
     *
     * Makes changes to the database 'data'
     *
     * @return bool
     */
    private function _changeData()
    {
        $db_traffic_log = Digitalus_Db_Table::getTableName('traffic_log');

        /*
        *******************************************************************************
        * changes to table `traffic_log`
        *******************************************************************************
        */
        // change type tables to InnoDB
        if ($this->_db->query("ALTER TABLE `$db_traffic_log` ENGINE=InnoDB")) {
            return true;
        }
        return false;
    }

    /**
     *
     * Creates the database 'groups'
     *
     * @return void
     */
    private function _createGroups()
    {
        $db_groups = Digitalus_Db_Table::getTableName('groups');

        /*
        *******************************************************************************
        * changes to table `groups`
        *******************************************************************************
        */
        // create table
        $this->_db->query("CREATE TABLE IF NOT EXISTS `$db_groups` (
            `name` varchar(30) NOT NULL,
            `parent` varchar(30) DEFAULT NULL,
            `label` varchar(30) DEFAULT NULL,
            `description` varchar(200) DEFAULT NULL,
            `acl_resources` text,
            PRIMARY KEY  (`name`),
            KEY `parent` (`parent`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

        // fill table
        $this->_db->query("INSERT INTO `$db_groups`
                (`name`, `parent`, `label`, `description`, `acl_resources`)
            VALUES
                ('guest',      NULL,    'Guest',               NULL, 'a:3:{s:8:\"404_page\";s:1:\"1\";s:12:\"site_offline\";s:1:\"1\";s:4:\"home\";s:1:\"1\";}'),
                ('admin',      'guest', 'Site Administrator',  NULL, 'a:3:{s:8:\"404_page\";s:1:\"1\";s:12:\"site_offline\";s:1:\"1\";s:4:\"home\";s:1:\"1\";}'),
                ('superadmin', NULL,    'Super Administrator', NULL, NULL)");
    }

    /**
     *
     * Makes changes to the database 'users'
     *
     * @return void
     */
    private function _changeUsers()
    {
        $db_users = Digitalus_Db_Table::getTableName('users');

        /*
        *******************************************************************************
        * changes to table `users`
        *******************************************************************************
        */
        // change type tables to InnoDB
        $this->_db->query("ALTER TABLE `$db_users` ENGINE=InnoDB");
        // insert field 'name', copy 'email' to 'name' and give user(1) the name 'administrator'
        $this->_db->query("ALTER TABLE `$db_users`
            ADD `name` VARCHAR(30) NULL AFTER `id`");
        $this->_db->query("UPDATE `$db_users`
            SET `name` = `email`");
        $this->_db->query("UPDATE `$db_users`
            SET `name` = 'administrator'
            WHERE `id` = 1 AND `role` = 'superadmin'");
        // insert field 'active' and set all existing users active
        $this->_db->query("ALTER TABLE `$db_users`
            ADD `active` TINYINT(1) NOT NULL DEFAULT 0 AFTER `name`");
        $this->_db->query("UPDATE `$db_users`
            SET `active` = 1");
        // set field `openid` NULL by default and set NULL where the openid is empty
        $this->_db->query("ALTER TABLE `$db_users`
            CHANGE `openid` `openid` VARCHAR(100) NULL DEFAULT NULL");
        $this->_db->query("UPDATE  `$db_users`
            SET `openid` = NULL
            WHERE `openid` = ''");
        // set 'guest' as default instead of 'admin'
        $this->_db->query("ALTER TABLE `$db_users`
            CHANGE `role` `role` VARCHAR(30) DEFAULT 'guest'");
        // change field type of `password`, etc.
        $this->_db->query("ALTER TABLE `$db_users`
            CHANGE `first_name` `first_name` VARCHAR(50) DEFAULT '',
            CHANGE `last_name`  `last_name`  VARCHAR(50) DEFAULT '',
            CHANGE `password`   `password`   VARCHAR(32) NOT NULL");
        // remove 'auto_increment' from `id`
        $this->_db->query("ALTER TABLE `$db_users`
            CHANGE `id` `id` INT(10) NOT NULL");
        // drop and (re-)create primary keys
        $this->_db->query("ALTER TABLE `$db_users`
            DROP PRIMARY KEY");
        $this->_db->query("ALTER TABLE `$db_users`
            ADD KEY  `fk_user_roles` (`role`),
            ADD PRIMARY KEY (`name`)");
    }

    /**
     *
     * Makes changes to the database 'pages'
     *
     * @return void
     */
    private function _changePages()
    {
        $db_pages = Digitalus_Db_Table::getTableName('pages');
        $db_users = Digitalus_Db_Table::getTableName('users');

        /*
        *******************************************************************************
        * changes to table `pages`
        *******************************************************************************
        */
        // insert `last_update`
        $this->_db->query("ALTER TABLE `$db_pages`
            ADD `last_update` TIMESTAMP NULL DEFAULT NULL AFTER `archive_date`");
        // change type of pages.publish_level, pages.is_home_page and pages.show_on_menu to ENUM/TINYINT
        $this->_db->query("ALTER TABLE `$db_pages`
            CHANGE `publish_level` `publish_level` ENUM('1', '11', '21') NULL DEFAULT '11',
            CHANGE `show_on_menu`  `show_on_menu`  TINYINT(1) NULL DEFAULT 0,
            CHANGE `parent_id`     `parent_id`     INT(11)         DEFAULT 0");
        // change type to match column 'id' of db 'users'
        $this->_db->query("ALTER TABLE `$db_pages`
            CHANGE `author_id` `author_id` INT(10) NOT NULL");
        // set 'author_id' = 1 to verify each 'pages' entry has a valid 'author_id'
        $this->_db->query("UPDATE `$db_pages`
            SET `author_id` = 1 WHERE `author_id` = 0");
        // create new column `user_name`
        $this->_db->query("ALTER TABLE `$db_pages`
            ADD `user_name` VARCHAR(30) NULL AFTER `id`");
        // fill new column `user_name` with corresponding values from table `users`
        $this->_db->query("UPDATE `$db_pages`
            LEFT JOIN
                (SELECT `id`, `name` FROM `$db_users` GROUP BY `id`) AS `$db_users` ON `$db_pages`.`author_id` = `$db_users`.`id`
            SET
                `$db_pages`.`user_name` = `$db_users`.`name`");
        // add index `user_name'
        $this->_db->query("ALTER TABLE `$db_pages`
            ADD INDEX `fk_parent_page` (`parent_id`),
            ADD INDEX `fk_page_author` (`user_name`)");
    }

    /**
     *
     * Creates the database 'user_bookmarks'
     *
     * @return void
     */
    private function _createUserBookmarks()
    {
        $db_users          = Digitalus_Db_Table::getTableName('users');
        $db_user_bookmarks = Digitalus_Db_Table::getTableName('user_bookmarks');
        $db_content_nodes  = Digitalus_Db_Table::getTableName('content_nodes');

        /*
        *******************************************************************************
        * changes to table `user_bookmarks`
        *******************************************************************************
        */
        // create table `user_bookmarks`
        $this->_db->query("DROP TABLE IF EXISTS `$db_user_bookmarks`");
        $this->_db->query("CREATE TABLE `user_bookmarks` (
            `id` int(11) NOT NULL auto_increment,
            `user_name` varchar(30) NOT NULL,
            `label` varchar(50) NOT NULL,
            `url` varchar(100) NOT NULL,
            PRIMARY KEY  (`id`),
            KEY `fk_user_bookmarks` (`user_name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        // fill the new table
        $this->_db->query("INSERT INTO `$db_user_bookmarks`
                (`user_name`, `label`, `url`)
            SELECT
                `parent_id`, `node`, `content`
            FROM
                `$db_content_nodes`
            WHERE
                `content_type` = 'bookmark'");
        // fill column `user_name` with corresponding values from table `users`
        $this->_db->query("UPDATE `$db_user_bookmarks`
            LEFT JOIN
                (SELECT `id`, `name` FROM `$db_users` GROUP BY `id`) AS `$db_users` ON `$db_user_bookmarks`.`user_name` = `$db_users`.`id`
            SET
                `$db_user_bookmarks`.`user_name` = `$db_users`.`name`");
    }

    /**
     *
     * Creates the database 'user_notes'
     *
     * @return void
     */
    private function _createUserNotes()
    {
        $db_users          = Digitalus_Db_Table::getTableName('users');
        $db_user_notes     = Digitalus_Db_Table::getTableName('user_notes');
        $db_content_nodes  = Digitalus_Db_Table::getTableName('content_nodes');

        /*
        *******************************************************************************
        * changes to table `user_notes`
        *******************************************************************************
        */
        // create table `user_notes`
        $this->_db->query("DROP TABLE IF EXISTS `$db_user_notes`");
        $this->_db->query("CREATE TABLE `$db_user_notes` (
            `id` int(11) NOT null auto_increment,
            `user_name` varchar(30) NOT NULL,
            `content` text,
            PRIMARY KEY (`id`),
            KEY `fk_user_notes` (`user_name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        // fill the new table
        $this->_db->query("INSERT INTO `$db_user_notes`
            (`user_name`, `content`)
        SELECT
            SUBSTRING_INDEX(`parent_id`, '_', -1), `content`
        FROM
            `$db_content_nodes`
        WHERE
            SUBSTRING_INDEX(`$db_content_nodes`.`parent_id`, '_', 1) = 'user'");
        // fill column `user_name` with corresponding values from table `users`
        $this->_db->query("UPDATE `$db_user_notes`
            LEFT JOIN
                (SELECT `id`, `name` FROM `$db_users` GROUP BY `id`) AS `$db_users` ON `$db_user_notes`.`user_name` = `$db_users`.`id`
            SET
                `$db_user_notes`.`user_name` = `$db_users`.`name`");
    }

    /**
     *
     * Makes changes to the database 'content_nodes'
     *
     * @return void
     */
    private function _changeContentNodes()
    {
        $db_content_nodes  = Digitalus_Db_Table::getTableName('content_nodes');
        $db_page_nodes     = Digitalus_Db_Table::getTableName('page_nodes');

        /*
        *******************************************************************************
        * changes to table `content_nodes`
        *******************************************************************************
        */
        // rename table to `page_nodes`
        $this->_db->query("RENAME TABLE `$db_content_nodes`
            TO `$db_page_nodes`");
        // change type of content_nodes.content to 'MEDIUMTEXT'
        $this->_db->query("ALTER TABLE `$db_page_nodes`
            CHANGE `content` `content` MEDIUMTEXT");
        // change type of field 'version'
        $this->_db->query("ALTER TABLE `$db_page_nodes`
            CHANGE `version` `language` ENUM('en','de','es','fr','hu','it','pl','ru','se') NOT NULL DEFAULT 'en'");
        // rename field 'node' to 'node_type'
        $this->_db->query("ALTER TABLE `$db_page_nodes`
            CHANGE `node` `node_type` VARCHAR(100) NOT NULL DEFAULT 'content'");
        // insert field 'page_id', `label`, `headline`
        $this->_db->query("ALTER TABLE `$db_page_nodes`
            ADD `page_id`  INT(11) NOT NULL AFTER `parent_id`,
            ADD `label`    VARCHAR(100) DEFAULT NULL AFTER `language`,
            ADD `headline` VARCHAR(100) DEFAULT NULL AFTER `label`");
        // add key to be able to set a foreign key later
        $this->_db->query("ALTER TABLE `$db_page_nodes`
            ADD KEY `fk_page_nodes` (`page_id`)");
        // drop the rows where parent is 'user'
        $this->_db->query("DELETE FROM `$db_page_nodes`
            WHERE
                SUBSTRING_INDEX(`parent_id`, '_', 1) = 'user'");
        // create table for the page ids
        $this->_db->query("UPDATE `$db_page_nodes`
            SET
                `page_id` = SUBSTRING_INDEX(`parent_id`, '_', -1)
            WHERE
                SUBSTRING_INDEX(`parent_id`, '_', 1) = 'page'");
        // drop the "old" column `parent_id`
        $this->_db->query("ALTER TABLE `$db_page_nodes`
            DROP `parent_id`");
    }

    /**
     *
     * Drops unneeded columns
     *
     * @return void
     */
    private function _dropUnneeded()
    {
        $db_pages      = Digitalus_Db_Table::getTableName('pages');
        $db_page_nodes = Digitalus_Db_Table::getTableName('page_nodes');
        $db_users      = Digitalus_Db_Table::getTableName('users');

        /*
        *******************************************************************************
        * drop unneeded columns and indexes
        *******************************************************************************
        */
        // drop column `pages`.`author_id`
        $this->_db->query("ALTER TABLE `$db_pages`
            DROP `author_id`,
            DROP `related_pages`,
            DROP `is_home_page`,
            DROP `design`");
        // drop column `page_nodes`.`content_type` and index `NODE_KEYS`
        $this->_db->query("ALTER TABLE `$db_page_nodes`
            DROP `id`,
            DROP `content_type`");
        $this->_db->query("ALTER TABLE `$db_page_nodes`
            DROP INDEX `NODE_KEYS`");
        // drop column `users`.`id`
        $this->_db->query("ALTER TABLE `$db_users`
            DROP `id`,
            DROP `acl_resources`");
    }

    /**
     *
     * Sets constraints and foreign keys
     *
     * @return void
     */
    private function _setForeignKeys()
    {
        $db_groups         = Digitalus_Db_Table::getTableName('groups');
        $db_users          = Digitalus_Db_Table::getTableName('users');
        $db_user_bookmarks = Digitalus_Db_Table::getTableName('user_bookmarks');
        $db_user_notes     = Digitalus_Db_Table::getTableName('user_notes');
        $db_pages          = Digitalus_Db_Table::getTableName('pages');
        $db_page_nodes     = Digitalus_Db_Table::getTableName('page_nodes');
        /*
        *******************************************************************************
        * set foreign keys
        *******************************************************************************
        */
        // add foreign key 'user_role' referencing column 'name' of db 'group'
        $this->_db->query("ALTER TABLE `$db_users`
            ADD CONSTRAINT `fk_user_roles` FOREIGN KEY (`role`) REFERENCES `$db_groups`(`name`) ON DELETE SET NULL ON UPDATE CASCADE");
        // add foreign key 'user_name' referencing column 'name' of db 'users'
        $this->_db->query("ALTER TABLE `$db_user_bookmarks`
            ADD CONSTRAINT `fk_user_bookmarks` FOREIGN KEY (`user_name`) REFERENCES `$db_users`(`name`) ON DELETE CASCADE ON UPDATE CASCADE");
        // add foreign key 'user_name' referencing column 'name' of db 'users'
        $this->_db->query("ALTER TABLE `$db_user_notes`
            ADD CONSTRAINT `fk_user_notes` FOREIGN KEY (`user_name`) REFERENCES `$db_users`(`name`) ON DELETE CASCADE ON UPDATE CASCADE");
        // add foreign key 'user_name' referencing column 'name' of db 'users'
        $this->_db->query("ALTER TABLE `$db_pages`
            ADD CONSTRAINT `fk_page_author` FOREIGN KEY (`user_name`) REFERENCES `$db_users`(`name`) ON DELETE NO ACTION ON UPDATE CASCADE");
        // add foreign key 'page_id' referencing column 'id' of db 'pages'
        $this->_db->query("ALTER TABLE `$db_page_nodes`
            ADD PRIMARY KEY (`page_id`, `language`)");
    }

    /**
     *
     * Optimises the databases
     *
     * @return void
     */
    public function optimise()
    {
        $db_data           = Digitalus_Db_Table::getTableName('data');
        $db_pages          = Digitalus_Db_Table::getTableName('pages');
        $db_page_nodes     = Digitalus_Db_Table::getTableName('page_nodes');
        $db_traffic_log    = Digitalus_Db_Table::getTableName('traffic_log');
        $db_users          = Digitalus_Db_Table::getTableName('users');
        $db_user_bookmarks = Digitalus_Db_Table::getTableName('user_bookmarks');
        $db_user_notes     = Digitalus_Db_Table::getTableName('user_notes');

        /* *****************************************************************
         * OPTIMIZE
         **************************************************************** */
        $this->_db->query("OPTIMIZE TABLE
            `$db_data` , `$db_pages` , `$db_page_nodes` , `$db_traffic_log` , `$db_users` , `$db_user_bookmarks` , `$db_user_notes`;");
    }

    /**
     *
     * Updates the config file
     *
     * @return bool
     */
    private function _updateConfig()
    {
        $config = new Digitalus_Installer_Config(false, 'v19');
        $config->loadFile();
        $data = $config->get();
        $data->production->constants->version = self::VERSION_NEW;
        $data->production->language->path     = './application/admin/data/languages';
        $data->production->filepath->icons    = 'images/icons/silk';
        $config->set($data);
        if ($config->save(self::getConfigPath())) {
            return true;
        }
        return false;
    }

    /**
     *
     * Removes unneeded directories
     *
     * @return void
     */
    private function _removeDirectories()
    {
        $directories = array('data', 'configs', 'models');
        foreach ($directories as $directory) {
            Digitalus_Filesystem_Dir::deleteRecursive(APPLICATION_PATH . '/' . $directory);
        }
    }
}