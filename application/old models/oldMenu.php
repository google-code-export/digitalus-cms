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
 * @version    $Id: Menu.php Sun Dec 23 11:03:50 EST 2007 11:03:50 forrest lyman $
 */

class Menu extends Content 
{
	/**
	 * content type
	 *
	 * @var string
	 */
	protected $_type = 'page';
	
	/**
	 * returns the current site menus
	 *
	 * @return zend_db_rowset
	 */
	function getMenus()
	{
		$sql = $this->_db->quoteInto("SELECT DISTINCT parent_id FROM pages WHERE content_type = ?", $this->_type);
		$menus = $this->_db->fetchAll($sql);
		foreach ($menus as $menu)
		{
			$ids[]= $menu->parent_id;
		}
		return $this->find($ids);
	}
	
	/**
	 * returns the position of the last menu item in a menu
	 * used for adding items to the menus 
	 * 
	 * @param int $menuId
	 * @return int
	 */
	public function getLastMenuPositionByMenu($menuId)
	{
		$sql = $this->_db->quoteInto("SELECT
			pages.position
			FROM
			pages
			WHERE
			pages.parent_id =  ?
			ORDER BY position DESC
			LIMIT 1", $menuId);
		$position = $this->_db->fetchRow($sql);
		return $position->position;
	}
    
	/**
	 * returns the items in the selected menu
	 * if $menusOnly is set to true this will only return items that are menus (have children)
	 *
	 * @param int $id
	 * @return zend_db_rowset
	 */
	function getMenuItems($id, $menusOnly = false)
	{
	    if($menusOnly){
	        $sql = $this->_db->quoteInto("SELECT DISTINCT parent_id FROM pages WHERE content_type = ?", $this->_type);
    		$menus = $this->_db->fetchAll($sql);
    		foreach ($menus as $menu)
    		{
    			$ids[]= $menu->parent_id;
    		}
    		if(is_array($ids)){
    		    $where[] = "id IN (" . implode(',', $ids) . ")";
    		}
		
	    }
		$where[] = $this->_db->quoteInto("parent_id = ?", $id);
		$order = "position";
		return $this->fetchAll($where, $order);
	}
	
	/**
	 * check to see if the selected item has a menu
	 *
	 * @param int $id
	 * @return bool
	 */
	function hasMenu($id)
	{
		$where[] = $this->_db->quoteInto('parent_id = ?', $id);
		if($this->fetchRow($where))
		{
			return true;
		}
	}
	
	/**
	 * updates the menu link for the current page
	 *
	 * @param int $pageId
	 * @param string $label
	 * @param int (bool, 0/1) $show
	 * @return bool
	 */
	public function updateMenuLink($pageId, $label, $show = 1)
	{
	    $row = $this->find($pageId)->current();
	    if($row)
	    {
    	    $row->label = $label;
    	    $row->show_on_menu = $show;
    	    return $row->save();
	    }
	}
	
	/**
	 * this function updates and sorts the menu items
	 * it expects all parameters to be passed as arrays
	 *
	 * @param array $id
	 * @param array $label
	 * @param array $showOnMenu
	 */
	function updateMenuItems($id, $label, $visibility)
	{
		if(is_array($id))
		{
			$count = count($id);
			for($i = 0; $i <= $count - 1; $i++)
			{
				$row = $this->find($id[$i])->current();
				$row->label = $label[$i];
				$row->show_on_menu = $visibility[$i];
				$row->position = $i;
				$row->save();
			}
		}
	}
}