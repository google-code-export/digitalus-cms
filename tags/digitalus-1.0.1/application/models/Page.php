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
 * @package    DSF_CMS_Models
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: Page.php Sun Dec 23 11:16:50 EST 2007 11:16:50 forrest lyman $
 */

/**
 * this class is the admin side of the content page model
 *
 */
class Page extends Content 
{
	/**
	 * conent type
	 *
	 * @var string
	 */
	protected $_type ="page";
	
	/**
	 * returns the current admin user's pages
	 *
	 * @param int $limit
	 * @return zend_db_rowset
	 */
	function getCurrentUsersPages($limit = 10)
	{
		$currentUser = DSF_Auth::getIdentity();
		return $this->getUsersPages($currentUser->id, $limit);
	}
	
	/**
	 * returns the selected admin user's pages
	 *
	 * @param int $id
	 * @param int $limit
	 * @return zend_db_rowset
	 */
	function getUsersPages($id, $limit = 10)
	{
		$where[] = $this->_db->quoteInto("author_id = ?", $id);
		$order[] = "create_date DESC";
		return $this->fetchAll($where, $order, $limit);
	}
	
	/**
	 * updates the page design (template and menu)
	 *
	 * @param int $pageId
	 * @param string $template
	 * @param string $layout
	 * @param string $userStyles
	 * @return bool
	 */
	public function updateDesign($pageId, $template = null, $layout = null, $userStyles = null)
	{
	    if($page = $this->find($pageId)->current())
	    {
	        if($template !== null)
	        {
	            $page->template_path = $template;
	        }
	        
	        if($layout !== null)
	        {
	            $page->layout_path = $layout;
	        }
	        
	        if($userStyles !== null){
	            $this->setUserStyles($pageId, $userStyles);
	        }
	        
	        return $page->save();
	    }
	}
	
	/**
	 * moves the selected page
	 *
	 * @param int $id
	 * @param int $parentId
	 * @return bool
	 */
	public function move($id, $parentId)
	{
	    $row = $this->find($id)->current();
	    if($row)
	    {
	        $row->parent_id = $parentId;
	        return $row->save();
	    }
	}
	
	/**
	 * adds a module to the selected page
	 *
	 * @todo figure out a cleaner way to do this
	 * @param int $id
	 * @param string $module
	 * @param string $action
	 * @param array $params
	 * @return bool
	 */
	public function addModule($id, $module, $action, $params)
	{
	    $prop = new Properties($id);
	    $prop->set('module', $module, 'modules');
	    $prop->set('action', $action, 'modules');
	    $prop->set('params', $params, 'modules');
  
	}
	
	public function setUserStyles($id, $styles)
	{
	    $p = new Properties($id);
	    $p->set('user_styles', $styles, 'design');
	}
}