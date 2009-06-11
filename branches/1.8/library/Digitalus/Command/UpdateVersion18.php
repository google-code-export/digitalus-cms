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
    }

    /**
     *
     * create pages and content nodes tables
     * validate that there are pages in the content table.
     * migrate content table rows to pages / content_nodes
     *
     */
    public function run()
    {
        $result = $this->_updateTemplateReferences();
        if (!$result) {
            $this->log('ERROR: could not update content template references.');
        } else {
            $this->log('Content template references updated OK.');
        }
    }

    /**
     * returns details about the current command
     *
     */
    public function info()
    {
        $this->log('The Update Version 18 command will update your database from version 1.5 to 1.8');
        $this->log('Params: none');
    }

    private function _updateTemplateReferences()
    {
        $this->_db->query("UPDATE pages SET content_template = 'block' WHERE content_template LIKE 'block_%'");
        $this->_db->query("UPDATE pages SET content_template = 'module' WHERE content_template LIKE 'module_%'");
        $this->_db->query("UPDATE pages SET content_template = 'default' WHERE content_template NOT IN ('block','module')");
        return true;
    }
}