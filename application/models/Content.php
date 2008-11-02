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
 * @version    $Id: Content.php Sun Dec 23 09:23:00 EST 2007 09:23:00 forrest lyman $
 */

class Content extends DSF_Db_Table 
{
	/**
	 * table name
	 *
	 * @var string
	 */
	protected $_name = 'content';
	
	/**
	 * required fields
	 *
	 * @var array
	 */
	protected $Required = array('title');
	
	/**
	 * text fields, will have stripTags filter applied
	 *
	 * @var array
	 */
	protected $Text = array('title');
	
	/**
	 * html fields, will have slashes added
	 *
	 * @var array
	 */
	protected $HTML = array('content');
	
	/**
	 * integer fields, will be evaluated as integers
	 * note that as in all php scripts if a string is passed
	 * php will parse this as a integer.  this means that if you 
	 * pass a string that can not be evaluated as a integer it will 
	 * be evaluated as a boolean ('test' is true, so will be set to 1)
	 * @var array
	 */
	protected $Int = array('parent_id','publish_level');
	
	/**
	 * an array of the current site index.
	 * this is not loaded by default
	 * you must call $this->indexPages();
	 *
	 * @var array
	 */
	private $_pageIndex;
	
	/**
	 * anything added to this block will be processed before an update or insert
	 *
	 */
	function before()
	{
	    $pDate = DSF_Filter_Post::get('publish_date');
	    if($pDate)
	    {
	        //convert the publish date to a timestamp
    	    $pDate = new Zend_Date($pDate);
    	    
    		$this->equalsValue('publish_date', $pDate->get(Zend_Date::TIMESTAMP ));
	    }
	    $aDate = DSF_Filter_Post::get('archive_date');
	    if($aDate)
	    {
	        //convert the publish date to a timestamp
    	    $aDate = new Zend_Date($aDate);
    		$this->equalsValue('archive_date', $aDate->get(Zend_Date::TIMESTAMP ));
	    }
	}

	/**
	 * anything added to this block will be processed before the db adapter inserts a row
	 *
	 */
	function beforeInsert()
	{
		$menuId = DSF_Filter_Post::get('parent_id');
		//step the menu position to the end
		$menu = new Menu();
		$this->equalsValue('position',$menu->getLastMenuPositionByMenu($menuId) + 1); 
		$user = DSF_Auth::getIdentity();
		$this->equalsValue('author_id',$user->id); 
		$this->equalsValue('content_type',$this->_type);
		$this->equalsNow('create_date'); 
	    //automatically add menu links
	    $s = new SiteSettings();
	    $addLinks = $s->get('addMenuLinks');
	    if($addLinks == 1){
	    	$this->equalsValue('show_on_menu', 1);
	    } 
	}
	
	/**
	 * anything added to this block will be processed after the adapter updates a row
	 *
	 */
	function beforeUpdate()
	{
		$this->equalsNow('edit_date');
		$user = DSF_Auth::getIdentity();
		$this->equalsValue('editor_id',$user->id); 
	}
	
	/**
	 * anything added to this block will be processed after either an insert or update
	 *
	 * @param unknown_type $id
	 */
	function after($id=false)
	{
        if(DSF_Filter_Post::get('link_to_menu') > 0){
            $menuItems = new MenuItems();
            $menuItems->createFromContent($id, $this->data['title'],DSF_Filter_Post::get('link_to_menu'));
        }
	}
	
	/**
	 * this serves as a wrapper for the relate content model's relate function
	 * this is done so it is available to any content item
	 *
	 * @param int $id
	 * @param int $relatedId
	 * @return bool
	 */
	public function relate($id, $relatedId)
	{
	    $r = new RelatedContent();
	    return $r->relate($id, $relatedId);
	}
	
	/**
	 * this serves as a wrapper for the relate content model's unrelate function
	 * this is done so it is available to any content item
	 *
	 * @param int $id
	 * @param int $relatedId
	 * @return bool
	 */
	public function unrelate($id, $relatedId)
	{
	    $r = new RelatedContent();
	    return $r->unrelate($id, $relatedId);
	    
	}
	
	/**
	 * creates loads the page index
	 * if you pass the optional parentId the index will start with this page
	 * if not it will index the whole site
	 * 
	 * @param integer $parentId
	 */
	public function indexPages($parentId = 0)
	{
		$children = $this->getChildren($parentId);
		
		foreach ($children as $child)
		{
			$parents = $this->getParents($child->id);
			$label =implode('/', $parents) . '/' . $child->title;
			$this->_pageIndex[$child->id] = DSF_Toolbox_String::stripLeading('/', $label);
			
			$children = $this->getChildren($child->id);
			if($children->count() > 0)
			{
				$submenu = $this->indexPages($child->id, $basePath);
			}
		}
	}
	
	/**
	 * search content items
	 *
	 * @param string $keywords
	 * @param array $filters
	 * @param string $order
	 * @param int $limit
	 * @return zend_db_rowset
	 */
	public function search($keywords, $filters = null, $order = 'title', $limit = null)
	{
        $select = $this->_db->select();
        $select->from(array('c' => 'content'), array('*'));
        $select->where($this->_db->quoteInto("c.content_type = ?", $this->_type));
        
        //add fulltext keyword search
        if(!empty($keywords))
        {
            $keywords = DSF_Toolbox_Regex::stripMultipleSpaces($keywords);
            $keywords = str_replace(' ',',', $keywords);
            $select->where("MATCH (c.content, c.tags) AGAINST ('{$keywords}')");
        }
        
        if(is_array($filters))
        {
            foreach ($filters as $filter) {
            	$select->where('c.' . $filter);
            }
        }
        
        $select->order = $order;
        if($limit && $limit > 0)
        {
            $select->limit = $limit;
        }
        //return $select->__toString();
        $stmt = $this->_db->query($select);
        return $stmt->fetchAll();
	}
	
	/**
	 * builds an array of the parents of the selected page
	 *
	 * @param int $id
	 * @return array
	 */
	public function getParents($id)
	{
		$row = $this->find($id)->current();
		$parentId = $row->parent_id;
		$parents = array();
		while($parentId > 0)
		{
			$row = $this->find($parentId)->current();
			$parents[] = $row->title;
			$parentId = $row->parent_id;
		}
		return array_reverse($parents);
	}
	
	/**
	* if menu request is set this will only return items that are selected to display on the menu
	*/
	
	/**
	 * returns the children of the current page (direct children, not recursive)
	 * if $menuRequest is set to true then this wil only return the items which are set to display on the menu
	 *
	 * @param int $pageId
	 * @param bool $menuRequest
	 * @return zend_db_rowset
	 */
	public function getChildren($pageId, $menuRequest = false)
	{
		$where[] = $this->_db->quoteInto("parent_id = ?", $pageId);
		if($menuRequest)
		{
			$where[] = "show_on_menu = 1";
			$where[] = "content_type='page'";
		}
		$order = "position";
		return $this->fetchAll($where, $order);
	}
	
	/**
	 * returns the current site index
	 *
	 * @return array
	 */
	public function getIndex()
	{
		$this->_pageIndex[0] = "Site Root";
		$this->indexPages(0);
		return $this->_pageIndex;
	}
	
	/**
	 * overloads the zend_db fetchAll method
	 * adds support for content types
	 *
	 * @param array $where
	 * @param mixed $order
	 * @param int $limit
	 * @param int $offset
	 * @return zend_db_rowset
	 * 
	 */
    function fetchAll($where=null,$order=null,$limit=null,$offset=null)
    {
    	if(!is_array($where) && $where !== null)
    	{
    		$where = array($where);
    	}
    	$type = $this->getType();
    	if($type)
    	{
    		$where[] = $this->_db->quoteInto('content_type = ?', $type);
    	}
    	return parent::fetchAll($where, $order, $limit, $offset);
    }
    
    /**
     * overloads the zend_db fetchRow method
     * adds support for content types
     *
     * @param array $where
     * @return zend_db row
     */
    function fetchRow($where=null)
    {
    	if(!is_array($where))
    	{
    		$where = array($where);
    	}
    	$type = $this->getType();
    	if($type)
    	{
    		$where[] = $this->_db->quoteInto('content_type = ?', $type);
    	}
    	
    	return parent::fetchRow($where);
    }
    
    /**
     * wraps the related content -> fetch related function
     *
     * @param int $id
     * @param zend_db_rowset $rowset
     * @param array $where
     * @param array $order
     * @param integer $limit
     * @return unknown
     */
    function fetchRelatedContent($id,  $where = null, $order = null, $limit = null)
    {
        $related = new RelatedContent();
        $row = $this->find($id);
        if($row){
            return $related->fetchRelated($row, $where, $order, $limit);
        }
    }
    
    /**
     * returns the error page
     *
     * @return stdClass
     */
    function getErrorPage()
    {
		$e = new ErrorLog();
		$e->log404();
		$item = new stdClass();
        $item->title = "Error";
        $item->content = "page does not exist";
    	return $item;
    }
    
    /**
     * returns the no auth page
     *
     * @return stdClass
     */
    function getNoAuthPage()
    {
		$e = new DSF_View_Error();
		$e->add('You must be logged in to view this page');
    	return $this->find(169)->current(); //member login
    }
    
    /**
     * 
     * this helper function returns the type for the current model
     * it validates:
     * the type is set in the model
     *
     * @return string
     */
    private function getType()
    {
		if(isset($this->_type))
		{
			return $this->_type;
		}
    }
    
}