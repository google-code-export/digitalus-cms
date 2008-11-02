<?php 

/**
 * DSF CMS
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
 * @category   DSF CMS
 * @package   DSF_Core_Library
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: ImportSitemap.php Tue Dec 25 19:57:20 EST 2007 19:57:20 forrest lyman $
 */

class DSF_Command_ImportSitemap extends DSF_Command_Abstract 
{
	/**
	 * the filepath to the sitemap.xml file
	 *
	 */
    const PATH_TO_SITEMAP = "./application/data/sitemap.xml";
    
    /**
     * db adapter
     *
     * @var zend_db_table adapter
     */
    private $_db;
    
    /**
     * the sitemap.xml file contents
     *
     * @var simpleXml object
     */
    private $_xml;
    
    /**
     * load the db adapter
     *
     */
    function __construct()
    {
        $this->_db = Zend_Db_Table::getDefaultAdapter();
    }
    
    /**
     * open the sitemap file
     * if successfull then initiate the import process
     *
     */
    function run()
    {
        $this->log('starting import process');
        if($this->_xml = simplexml_load_file(self::PATH_TO_SITEMAP))
        {
           $this->load();
        }else{
            $this->log("ERROR: error loading sitemap file");
        }
    }
    
    /**
     * returns details about the current command
     *
     */
    function info()
    {
        $this->log("The import sitemap command will import an xml sitemap.  This file should be located in application/data/ and be named sitemap.xml.");
    }
    
    /**
     * performs the sitemap import
     * you can optionaly pass a xml node (used for the recursive functionality)
     * 
     * @param simpleXml object $node
     * @param int $parentId
     */
    private function load($node = false, $parentId = 0)
    {
        $this->log('loading page nodes');
        if(!$node)
        {
            $node = $this->_xml;
        }
        
        foreach ($node->page as $page)
        {            
            //insert the page
            $pageId = $this->addPage((string)$page->name, $parentId);
   
            if($page->subPages)
            {
                $this->load($page->subPages, $pageId);
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
    private function addPage($page, $parentId = 0)
    {
        if(!$parentId > 0)
        {
            $parentId = 0;
        }
        $sql = "SELECT id FROM content WHERE title = '{$page}' AND content_type = 'page' AND parent_id = " . $parentId;
        $exists = $this->_db->fetchRow($sql);

        if($exists)
        {
            //the page already exists
            $this->log("ignoring " . $page . ', page already exists in this location');
            return $exists->id;
        }else{
            $data = array(
                'content_type' => 'page',
                'title' =>  $page,
                'label' =>  $page,
                'parent_id' => $parentId
            );
            if($this->_db->insert('content', $data))
            {
                $this->log("inserting " . $page);
                return $this->_db->lastInsertId();
            }else{
                $this->log("ERROR: an error occured inserting " . $page);
                return $parentId;
            }
        }
    }
}