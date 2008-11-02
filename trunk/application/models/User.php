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
 * @version    $Id: User.php Mon Dec 24 20:38:38 EST 2007 20:38:38 forrest lyman $
 */

class User extends DSF_Db_Table 
{
	const SUPERUSER_ROLE = 'superadmin';
	/**
	 * table name
	 *
	 * @var string
	 */
	protected $_name = 'users';
	
	/**
	 * required fields
	 *
	 * @var array
	 */
	protected $Required = array('email','first_name','last_name');
	
	/**
	 * unique fields
	 *
	 * @var array
	 */
	protected $Unique = array('email');
	
	/**
	 * text fields.  these fields are filtered with zend_filter_striptags
	 *
	 * @var array
	 */
	protected $Text = array('first_name','last_name','role');
	
	/**
	 * integer fields.  all fields will be converted to integers
	 *
	 * @var array
	 */
	protected $Int = array('user_group_id');
	
	/**
	 * numeric fields.  all fields filtered as floats / decimals
	 *
	 * @var array
	 */
	protected $Number = array();
	
	/**
	 * email fields
	 *
	 * @var array
	 */
	protected $Email = array('email');	
	
	/**
	 * run before insert or update
	 *
	 */
	function before()
	{
	}
	
	/**
	 * runs before insert
	 *
	 */
	function beforeInsert()
	{
		//validate password
		$newPwd = DSF_Filter_Post::get('newPassword');
		$confirm = DSF_Filter_Post::get('newConfirmPassword');
		if($newPwd == $confirm)
		{
			$this->data['password'] = md5($newPwd);			
		}else{
			$e = new DSF_View_Error();
			$e->add('Your new password does not match your confirmation password');
		}
	}

	/**
	 * runs before update
	 *
	 */
	function beforeUpdate()
	{ 
		
		//if 0 is passed as the id then set the id to the current users
		$id = DSF_Filter_Post::int('id');
		if(0 == $id) {
			$currentUser = $this->getCurrentUser();
			$this->data['id'] = $currentUser->id;
		}
		
		//overload the unique email validation if the current user has not changed their email address
		$curr = $this->find($this->data['id'])->current();
		if($curr->email == DSF_Filter_Post::raw('email')){
			unset($this->Unique[array_search('email',$this->Unique)]);
		}
		
		//update the password
		if(DSF_Filter_Post::int('update_password') == 1)
		{ 
			$newPwd = DSF_Filter_Post::get('newPassword');
			$confirm = DSF_Filter_Post::get('newConfirmPassword');
			if($newPwd == $confirm)
			{
				$this->data['password'] = md5($newPwd);
				
			}else{
				$this->errors->add('Your new password does not match your confirmation password');
			}
		}else{
			unset($this->data['password']);
		}
		
		//Zend_Debug::dump($this->data);die();
	}
	
	public function updateAclResources($userId, $resourceArray) {
	    $data['acl_resources'] = serialize($resourceArray);
	    $where[] = $this->_db->quoteInto("id = ?", $userId);
	    return $this->update($data, $where);	    
	}
	
	public function getAclResources($userRowset) {
	    return unserialize($userRowset->acl_resources);
	}
	
	/**
	 * returns the complete user row for the currently logged in user
	 * @return zend_db_row
	 */
	function getCurrentUser()
	{
		$currentUser = DSF_Auth::getIdentity();
		if($currentUser) {
		  return $this->find($currentUser->id)->current();
		}
	}
	
	
	public function getCurrentUsersAclResources()
	{
	   $currentUser = $this->getCurrentUser();
	   if($currentUser) {
	       return $this->getAclResources($currentUser);   
	   } 
	}
	
	public function getCurrentUsersModules()
	{
		return $this->getUsersModules($this->getCurrentUser());
	}
	
	public function getUsersModules($userRowset) {
		$user = $this->getCurrentUser();
		$resources = $this->getAclResources($userRowset);
		if(is_array($resources)) {
			foreach ($resources as $k => $v) {
				if(1 == $v || self::SUPERUSER_ROLE == $user->role) {
					$parts = explode('_', $k);
					if('mod' == $parts[0]){
						$key = $parts[1];
						$modules[$key] = $key;
					}
				}
			}
		}
		if(is_array($modules)) {
			return $modules;
		}
	}
	
	/**
	 * this function queries a users permissions
	 * 
	 * the resource should be in the module_controller_action format
	 * 
	 * if strict = true then this requires an exact match
	 * example: news_article != news_article_edit
	 * 
	 * if strict = false then it will add wildcards 
	 * example: news_article == news_article_edit
	 * 
	 * if user is not set then the query will be run on the current user
	 * 
	 * @param string $resource
	 * @param boolean $strict
	 * @param integer $user
	 * @return boolean
	 */
	public function queryPermissions($resource, $strict = false, $userId = null)
	{
		if($userId !== null) {
			$user = $this->find($userId)->current();
			if(!$user){
				return false;
			}
			$resources = $this->getAclResources($user);
		}else{
			$resources = $this->getCurrentUsersAclResources();
		}
		
		if(is_array($resources)) {
			if($strict) {
				if(array_key_exists($resource, $resources) && 1 == $resources[$resource]) {
					return true;
				}
			}else{
				$len = strlen($resource);
				foreach ($resources as $r => $v) {
					if(1 == $v && $resource == substr($r, 0, $len)) {
						return true;
					}
				}
			}
		}	
		return false;
	}
	
	function getUserByUsername($userName)
	{
	    $where[] = $this->_db->quoteInto("email = ?", $userName);
	    return $this->fetchRow($where);
	}
	
	/**
	 * @since 0.8.7
	 * 
	 * returns a hash of the current users
	 * their id is the key and their first_name . ' ' . last_name is the value
	 *
	 */
	public function getUserNamesArray()
	{
	    $users = $this->fetchAll();
	    foreach ($users as $user){
	        $usersArray[$user->id] = $user->first_name . ' ' . $user->last_name;
	    }
	    return $usersArray;
	}
	
	public function copyPermissions($from, $to){
	    $fromUser = $this->find($from)->current();
	    $toUser = $this->find($to)->current();
	    $toUser->acl_resources = $fromUser->acl_resources;
	    return $toUser->save();
	}
	
}