<?php


/**
 * DSF CMS
 * 
 * DESCRIPTION
 * 
 * this class manages the page properties.  these properties are stored in 
 * serialized dsf_datalist objects in the properties field.
 * this allows a very flexible data storage source for your page specific data
 * you can store an unlimited number of levels of data and virtually any data type (array, xml, object, etc)
 * this is only appropriate for use where you will not need to use the values 
 * as parameters for a query, as this field is not queriable
 * 
 * to store you own data in the properties use the user_data group.
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
 * @version    $Id: Properties.php Sun Dec 23 11:25:26 EST 2007 11:25:26 forrest lyman $
 */


class Properties extends Content  
{
	/**
	 * the predefined property groups
	 *
	 */
	private $_propertyGroups = array(
	    'general',
		'modules',
		'meta_data',
		'user_data'
	);
	
	/**
	 * the current content row
	 *
	 * @var zend_db_row
	 */
    private $_row;
    
    /**
     * the property list
     *
     * @var dsf_datalist
     */
    public $list;
    
    /**
     * loads the selected page's properties
     * this is optional, so if you already have a page open you can use the open
     * method (saves the overhead of loading the row again)
     * it then validates the existance of the property groups, and creates them if necessary
     *
     * @param int $contentId
     */
    public function __construct($contentId = 0)
    {
    	parent::__construct();
    	if($contentId){
	    	$this->load($contentId);
	    	// create the property groups if they do not exist
	    	foreach ($this->_propertyGroups as $group){
	    		if(!$this->list->hasGroup($group)){
	    			$this->list->addGroup($group);
	    		}
	    	}
    	}
    }
    
    /**
     * loads the page then calls the open properties method
     *
     * @param int $contentId
     * @return dsf_datalist
     */
	public function load($contentId)
	{
	    $this->_row = $this->find($contentId)->current();
	    if(empty($this->_row->properties))
	    {
	        $this->list = new DSF_Data_List();
	    }else{
    	    $this->open($this->_row->properties);
	    }
	    return $this->list;
	}
	
	/**
	 * loads the dsf_datalist for the page properties
	 *
	 * @param serialized dsf_datalist $properties
	 * @return dsf_datalist
	 */
	public function open($properties)
	{
	    if(!empty($properties)){
	        $this->list = new DSF_Data_List($properties);
	        return $this->list;
	    }
	}
	
	/**
	 * saves the page properties
	 *
	 */
	public function save()
	{
	    $this->_row->properties = $this->list->toString();
	    $this->_row->save();
	}
	
	/**
	 * get a property
	 *
	 * @param string $key
	 * @return dsf_datalist
	 */
	public function get($key, $group = 'general')
	{
	    if(isset($this->list->items->$group)){
	        $groupObj = $this->list->items->$group;
	        return $groupObj->items->$key;
	    }
	}
	
	public function getGroup($group, $create = false)
	{
	    if(isset($this->list->items->$group)){
	        $groupObj = $this->list->items->$group;
	        return $groupObj;
	    }elseif($create == true){
	        return $this->list->addGroup($group);
	    }
	}
	
	/**
	 * set a property value
	 * it defaulst to the general property group.  
	 * if this is set to a group that does not exist then it will create the group
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param string $group
	 */
	public function set($key, $value, $group = 'general')
	{
	    if(!$this->list->hasGroup($group)){
	        $this->list->addGroup($group);
	    }
	    $groupObj =  $this->list->items->$group;
	    $groupObj->addItem($key, $value);
	    $this->save();
	}
}