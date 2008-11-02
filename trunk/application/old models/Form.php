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
 * @version    $Id: Form.php Sun Dec 23 11:03:15 EST 2007 11:03:15 forrest lyman $
 */

/**
 * @todo figure out a way to write back from the sqlite database
 *
 */
class Form extends Content 
{
	
	/**
	 * this is the form row
	 *
	 * @var zend_db_row
	 */
	protected $_form;
	
	/**
	 * the form stores the data in the userdata property
	 *
	 * @var DSF_model, Property
	 */
	protected $_properties;
	
	/**
	 * the path to the sqlite form_data database
	 *
	 * @var string
	 */
	private $_sqliteDb = './application/data/sqlite/form_data.sqlite2';
	
	/**
	 * the main sqlite adapter
	 *
	 * @var zend_db_adapter
	 */
	protected $_sqliteAdapter;
	
	/**
	 * the sqlite table
	 *
	 * @var sqlite_table
	 */
	private $_table;
	
	/**
	 * zend_db_sqlite adapter to the table
	 *
	 * @var zend_db_adapter
	 */
	protected $_formData;
	
	/**
	 * if memory is set to true then this will load the data into a memory based database
	 * otherwise it will create a db $this->sqliteDb
	 *
	 * @param unknown_type $memory
	 */
	public function __construct($memory = true)
	{
	    parent::__construct();
	    //set up the sqlite adapter
	    if(!$memory){
    	    try{
                $sqlite_db = new SQLiteDatabase($this->_sqliteDb, 0666);
            
            }catch(Exception $e){
                die($e->getMessage());
            
            }
    	    $this->_sqliteAdapter = new Zend_Db_Adapter_Pdo_Sqlite(array(
    	       'dbname'    =>  $this->_sqliteDb
    	    ));
	    }else{
    	    $this->_sqliteAdapter = new Zend_Db_Adapter_Pdo_Sqlite(array(
    	       'dbname'    =>  ':memory:'
    	    ));
	    }
	}
	
	/**
	 * finds the current form and sets it
	 *
	 * @param int $formId
	 * @return bool, whether the form was found
	 */
	public function setForm($formId)
	{
	    $this->_form = $this->find($formId)->current();
	    if($this->_form){
	        //set the properties
	        $this->_properties = new Properties($formId);
	        return true;
	    }
	}
	
	/**
	 * adds a row of data to the current form
	 *
	 * @param associative array $data
	 */
	public function addRows($data)
	{
	    if($this->_form){
	        $userData = $this->getData();
	        
	        //we use the current timestamp as a unique identifier for each row
	        //this will be more helpfull in the future than some random number
	        $userData->addItem($this->makeId(), $data);
	        //set the properties
	        $this->_properties->save();
	        return true;
	    }
	}
	
	/**
	 * updates the specified row if it exists
	 *
	 * @todo fill in function
	 * @param timestamp $row
	 * @param unknown_type $data
	 */
	public function updateRow($row, $data)
	{
	    // @todo: add this function
	}
	
	/**
	 * deletes a row
	 * 
	 * @todo fill in function
	 *
	 * @param unknown_type $row
	 */
	public function deleteRow($row)
	{
	    
	}
	
	/**
	 * this builds the table if it does not exist
	 * if it does exist then 
	 * @todo test, benchmark, and optimize this as much as possible.  it is curretnly looking pretty good.  43,000 records in 13.86 seconds
	 *
	 * @return unknown
	 */
	public function getDataTable()
	{
	    $list = $this->getData();
	    //Zend_Debug::dump($list);
	    if(!$this->_table){
	        //load the table
    	    foreach ($list->items as $id => $row){
    	         if(!$this->_table){
    	             $this->makeTable(array_keys($row));
    	         }
    	         $row['id'] = $id;
    	         $timeParts = explode('_', $id);
    	         if(count($timeParts) === 2)
    	         {
    	             //the time entered is the first part
    	             $row['time_entered'] = $timeParts[0];
    	         }
                 $this->_table->insert($row);	
 
    	    }
	    }

	    return $this->_table;
	}
	
	/**
	 * returns the user_data list
	 *
	 * @return DSF_Data_List
	 */
	private function getData()
	{
	    return $this->_properties->get('user_data');
	}
	
	/**
	 * returns a unique id with the timestamp and a number 1 - 999999
	 *
	 * @return string
	 */
	private function makeId()
	{
	    // if two people pick out a number 1 in a million in the same second 
	    // there is enough traffic on the form to warrent its own module
	    $id = time() . "_" . rand(1, 9999999);
	    return $id;
	}
	
	/**
	 * creates the data table
	 *
	 * @param array $fields
	 */
	private function makeTable($fields)
	{
	    if(is_array($fields)){
	        //add the submit time field
	        array_unshift($fields, "id");
	        array_unshift($fields, "time_entered");
	        
	        //create the table
	        $sql = "CREATE TABLE form_data (" . implode(', ', $fields) . ")";
	        $this->_sqliteAdapter->query($sql);	
	        //$this->_table = true;
	        $this->_table = new FormData($this->_sqliteAdapter);
	    }
	    
	}
	
	
}