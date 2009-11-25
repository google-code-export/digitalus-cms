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
 * @category   Digitalus CMS
 * @package   Digitalus_Core_Library
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: UpdateDatabase.php Mon Aug 18 EST 2008 19:57:20 forrest lyman $
 */

class Digitalus_Command_UpdateVersion18 extends Digitalus_Command_Abstract
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
        $this->log($this->view->getTranslation('The Update Version 18 command will update your database from version 1.5 to 1.8'));
        $this->log($this->view->getTranslation('Params: none'));
    }

    private function _updateTemplateReferences()
    {
        $this->_db->query("UPDATE `"      . Digitalus_Db_Table::getTableName('pages') . "` SET `content_template` = 'block' WHERE `content_template` LIKE 'block_%'");
        $this->_db->query("UPDATE `"      . Digitalus_Db_Table::getTableName('pages') . "` SET `content_template` = 'module' WHERE `content_template` LIKE 'module_%'");
        $this->_db->query("UPDATE `"      . Digitalus_Db_Table::getTableName('pages') . "` SET `content_template` = 'default' WHERE `content_template` NOT IN ('block','module')");
        $this->_db->query("UPDATE `"      . Digitalus_Db_Table::getTableName('pages') . "` SET `content_template` = 'default_default' WHERE `namespace` = 'content' AND `content_template` = 'default'");
        $this->_db->query("UPDATE `"      . Digitalus_Db_Table::getTableName('pages') . "` SET `content_template` = 'default_default' WHERE `namespace` = 'content' AND `content_template` = 'module'");
        $this->_db->query("ALTER TABLE `" . Digitalus_Db_Table::getTableName('pages') . "` ADD `publish_date` INT(11) NOT NULL AFTER `create_date`");
        $this->_db->query("ALTER TABLE `" . Digitalus_Db_Table::getTableName('pages') . "` ADD `archive_date` INT(11) NOT NULL AFTER `publish_date`");
        $this->_db->query("ALTER TABLE `" . Digitalus_Db_Table::getTableName('pages') . "` ADD `publish_level` INT(11) NOT NULL AFTER `archive_date`");
        return true;
    }
}