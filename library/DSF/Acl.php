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
 * @version    $Id: Acl.php Tue Dec 25 21:39:35 EST 2007 21:39:35 forrest lyman $
 */

class DSF_Acl extends Zend_Acl
{
	/**
	 * load the acl resources and set up permissions
	 *
	 */
    public function __construct()
    {
        $this->addRole(new Zend_Acl_Role('admin'));
        $this->addRole(new Zend_Acl_Role('superadmin'),'admin');
        
        $this->add(new Zend_Acl_Resource('index'));
        $this->add(new Zend_Acl_Resource('error'));
        $this->add(new Zend_Acl_Resource('user'));
        $this->add(new Zend_Acl_Resource('auth'));
        $this->add(new Zend_Acl_Resource('site'));
        $this->add(new Zend_Acl_Resource('support'));
        
        //load modules

        //user specific permissions
        $db = Zend_Db_Table::getDefaultAdapter();
        $resources = $db->fetchAll("SELECT controller FROM acl_resources");
	    foreach ($resources as $resource)
	    {
	    	$this->add(new Zend_Acl_Resource($this->getResourceFromRow($resource)));
	    }
        
        //everybody
        $this->allow(null, "auth", "login");
        $this->allow(null, "auth", "logout");
        $this->allow(null, "auth", "resetPassword");
        $this->allow(null, "error");
        
        //site administrators
        $this->allow('admin','index');
        $this->allow('admin','support');

        //user specific permissions
        $user = DSF_Auth::getIdentity();
        if($user)
        {
	        $roles = $db->fetchAll("SELECT controller FROM acl_resources WHERE id IN (" . $user->acl_roles . ")");
		    foreach ($roles as $role)
		    {
		    	$this->allow('admin',$this->getResourceFromRow($role));
		    }
        }
        
        $this->allow('admin','support');
        
        //grant the super admin access to everything
       	$this->allow('superadmin');

    }
    
    private function getResourceFromRow($row)
    {
        if($row->admin_section == 'module'){
            return 'mod_' . $row->controller;
        }else{
            return $row->controller;
        }
    }
}