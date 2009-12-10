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
 * @version    $Id: ImportSitemap.php Tue Dec 25 19:57:20 EST 2007 19:57:20 forrest lyman $
 */

class Digitalus_Command_InstallModule extends Digitalus_Command_Abstract
{
    /**
     * the filepath to the site modules
     *
     * @var string
     */
    private $_pathToModules = './application/modules';

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
     * validate that the module exists.  if it does then add it to the db (acl_resources)
     *
     */
    public function run($params = null)
    {
        if (isset($params['moduleName'])) {
            $module = $params['moduleName'];
            $this->log($this->view->getTranslation('Validating module directory'));
            if (is_dir($this->_pathToModules . '/' . $module)) {
                $this->log($this->view->getTranslation('Module exists. Installing ...'));
                //default to the module name for the label
                if (isset($params['label'])) {
                    $label = $params['label'];
                } else {
                    $label = $module;
                }
                $sql  = "INSERT INTO " . Digitalus_Db_Table::getTableName('acl_resources') . " (label, controller, admin_section) VALUES ";
                $sql .= "('{$label}', '{$module}', 'module')";
                $this->_db->query($sql);
                $this->log($this->view->getTranslation('Module installed'));
            } else {
                $this->log($this->view->getTranslation('ERROR: Could not load module directory'));
            }
        } else {
            $this->log($this->view->getTranslation('ERROR: Invalid request'));
        }

    }

    /**
     * returns details about the current command
     *
     */
    public function info()
    {
        $this->log($this->view->getTranslation('The install module command will install a module in the database if the module is installed properly.'));
        $this->log($this->view->getTranslation('Params: moduleName (string), label (string, optional)'));
    }

    /**
     * performs the sitemap import
     * you can optionaly pass a xml node (used for the recursive functionality)
     *
     * @param simpleXml object $node
     * @param int $parentId
     */
    private function _load($node = false, $parentId = 0)
    {
        $this->log($this->view->getTranslation('loading page nodes'));
        if (!$node) {
            $node = $this->_xml;
        }

        foreach ($node->page as $page) {
            //insert the page
            $pageId = $this->_addPage((string)$page->name, $parentId);

            if ($page->subPages) {
                $this->_load($page->subPages, $pageId);
            }
        }
    }

    /**
     * inserts the new page
     * defaults to inserting it into the root
     * @todo add these pages to the relations table
     *
     * @param unknown_type $parentId
     * @return unknown
     */
    private function _addPage($page, $parentId = 0)
    {
        if (!$parentId > 0) {
            $parentId = 0;
        }
        $sql = "SELECT id FROM " . Digitalus_Db_Table::getTableName('content') . " WHERE title = '{$page}' AND content_type = 'page' AND parent_id = " . $parentId;
        $exists = $this->_db->fetchRow($sql);

        if ($exists) {
            //the page already exists
            $this->log($this->view->getTranslation('ignoring') . ' ' . $page . ', ' . $this->view->getTranslation('page already exists in this location'));
            return $exists->id;
        } else {
            $data = array(
                'content_type' => 'page',
                'title' =>  $page,
                'label' =>  $page,
                'parent_id' => $parentId
            );
            if ($this->_db->insert(Digitalus_Db_Table::getTableName('content'), $data)) {
                $this->log($this->view->getTranslation('inserting') . ' ' . $page);
                return $this->_db->lastInsertId();
            } else {
                $this->log($this->view->getTranslation('ERROR: an error occured inserting') . ' ' . $page);
                return $parentId;
            }
        }
    }
}