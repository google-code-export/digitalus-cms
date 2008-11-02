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
		//add the user's permissions
		$resources = DSF_Filter_Post::raw('resource');
		if(!empty($resources))
		{
			foreach ($resources as $k => $v)
			{
				if(1 == $v)
				{
					$perms[] = $k;
				}
			}
			if(is_array($perms))
			{
				$this->data['acl_roles'] = implode(',', $perms);
			}
		}
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
		}
	}
	
	/**
	 * returns the current user's acl roles
	 *
	 * @return zend_db_rowset
	 */
	function getCurrentUsersAclRoles()
	{
		$user = $this->getCurrentUser();
		$permArray = explode(',', $user->acl_roles);
		$r = new AclResource();
		return $r->find($permArray);
	}
	
	/**
	 * returns the complete user row for the currently logged in user
	 * @return zend_db_row
	 */
	function getCurrentUser()
	{
		$currentUser = DSF_Auth::getIdentity();
		return $this->find($currentUser->id)->current();
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
	
}