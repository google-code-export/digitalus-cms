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
 * @package    DSF_CMS_Controllers
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: UserController.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
 */

class Core_UserController extends Zend_Controller_Action
{
	
	function init()
	{
    	//set the admin section
		$this->view->adminSection = 'site';
	}
	
	/**
	 * render the user management interface
	 *
	 */
	function indexAction()
	{
	}

	/**
	 * open a user for editing
	 *
	 */
	function openAction()
	{
	   
       $id = $this->_request->getParam('id', 0);
       if($id > 0){
           $u = new User();
    	   
    	   $this->view->user = $u->find($id)->current();
    	   
    	   //if the full page form is set then the whole page will post to this url
    	   $this->view->fullPageForm = "/admin/user/edit";
       }
	}

	/**
	 * add a new user
	 *
	 */
	function createAction()
	{
	    if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
    		$u = new User();
            $user = $u->insertFromPost();
            $e = new DSF_View_Error();
            if(!$e->hasErrors()){
                $url = '/admin/user/open/id/' . $user->id;
                $this->_redirect($url);
            }else{
            	$storage = new DSF_Data_Storage();
            	$storage->savePost();
            }
	    }
	}
	
	/**
	 * edit an existing user
	 *
	 */
	function editAction()
	{
        $u = new User();
        $user = $u->updateFromPost();
        if(DSF_Filter_Post::get('done') == 1)
        {
        	$url = '/admin/site';
        }else{
       		 $url = '/admin/user/open/id/' . $user->id;
        }

      $this->_redirect($url);

    }
	
    /**
     * delete a user
     *
     */
	function deleteAction()
	{
	   $id = $this->_request->getParam('id');
	   $u = new User();
	   $u->delete("id = " . $id);
	   $url = "/admin/site";
       $this->_redirect($url);
	}

}