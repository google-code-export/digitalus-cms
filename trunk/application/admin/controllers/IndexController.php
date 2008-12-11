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
 * @version    $Id: IndexController.php Mon Dec 24 20:50:29 EST 2007 20:50:29 forrest lyman $
 */

class Admin_IndexController extends Zend_Controller_Action
{

	function init()
	{
	    $this->view->breadcrumbs = array(
	       'Home' =>   $this->getFrontController()->getBaseUrl() . '/admin'
	    );
	}
	
	/**
	 * displays the admin dashboard
	 *
	 */
	function indexAction()
	{
	    $notes = new Note();
	    $this->view->notes = $notes->getUsersNotes();
	    $bookmark = new Bookmark();
	    $this->view->bookmarks = $bookmark->getUsersBookmarks();
	    $content = new Page();
        $this->view->pages = $content->getCurrentUsersPages();
        $user = new User();
        $this->view->identity = $user->getCurrentUser();
	}
	
	function notesAction()
	{
	    $notes = new Note();
	    $myNotes = DSF_Filter_Post::get('content');
	    $notes->saveUsersNotes($myNotes);
	    $this->_redirect('admin/index');
	}
	
	function bookmarkAction()
	{
	    $url = $this->_request->getParam('url');
	    $label = $this->_request->getParam('label');
	    $bookmark = new Bookmark();
	    $bookmark->addUsersBookmark($label, $url);
	}
	
	function deleteBookmarkAction()
	{
	    $id = $this->_request->getParam('id');
	    $bookmark = new Bookmark();
	    $bookmark->deleteBookmark($id);
	    $this->_redirect('admin/index');
	}
	
	function testAction()
	{
	    
	}
}


