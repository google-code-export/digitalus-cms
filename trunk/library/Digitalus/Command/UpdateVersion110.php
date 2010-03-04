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
    public function run($params = null)
    {
        $result = $this->_updateTemplateReferences();
        if (!$result) {
            $this->log($this->view->getTranslation('ERROR: could not update content template references.'));
        } else {
            $this->log($this->view->getTranslation('Content template references updated OK.'));
        }
    }

    /**
     * returns details about the current command
     *
     */
    public function info()
    {
        $this->log($this->view->getTranslation('The Update Version 110 command will update your database from version 1.9 to 1.10'));
        $this->log($this->view->getTranslation('Params: none'));
    }

    private function _updateTemplateReferences()
    {
        // change type of pages.publish_level, pages.is_home_page and pages.show_on_menu to TINYINT
        $this->_db->query("ALTER TABLE `" . Digitalus_Db_Table::getTableName('pages') . "`"
                         . " CHANGE `publish_level` `publish_level` TINYINT(2) NULL DEFAULT NULL,"
                         . " CHANGE `is_home_page`  `is_home_page`  TINYINT(1) NULL DEFAULT NULL,"
                         . " CHANGE `show_on_menu`  `show_on_menu`  TINYINT(1) NULL DEFAULT NULL");
        // change type of content_nodes.content to 'MEDIUMTEXT'
        $this->_db->query("ALTER TABLE `" . Digitalus_Db_Table::getTableName('content_nodes') . "` CHANGE `content` `content` MEDIUMTEXT");
        // change type tables to InnoDB
        $this->_db->query("ALTER TABLE `" . Digitalus_Db_Table::getTableName('traffic_log') . "` ENGINE=InnoDB");
        $this->_db->query("ALTER TABLE `" . Digitalus_Db_Table::getTableName('users') . "` ENGINE=InnoDB");
        // insert field 'username', copy 'email' to 'username' and give user(1) the username 'administrator'
        $this->_db->query("ALTER TABLE `" . Digitalus_Db_Table::getTableName('users') . "` ADD `username` VARCHAR(30) NOT NULL AFTER `id`");
        $this->_db->query("UPDATE `" . Digitalus_Db_Table::getTableName('users') . "` SET `username` = `email`");
        $this->_db->query("UPDATE `" . Digitalus_Db_Table::getTableName('users') . "` SET `username` = 'administrator' WHERE `id` = 1 AND `role` = 'superadmin'");
        // insert field 'active' and set all existing users active
        $this->_db->query("ALTER TABLE `" . Digitalus_Db_Table::getTableName('users') . "` ADD `active` TINYINT(1) NOT NULL DEFAULT '0' AFTER `id`");
        $this->_db->query("UPDATE `" . Digitalus_Db_Table::getTableName('users') . "` SET `active` = '1'");

        return true;
    }
}