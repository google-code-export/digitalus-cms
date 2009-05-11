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

    public function init()
    {
        $this->view->breadcrumbs = array(
           $this->view->GetTranslation('Home') => $this->getFrontController()->getBaseUrl() . '/admin'
        );
    }

    /**
     * displays the admin dashboard
     *
     */
    public function indexAction()
    {
        $notes = new Model_Note();
        $this->view->notes = $notes->getUsersNotes();
        $bookmark = new Model_Bookmark();
        $this->view->bookmarks = $bookmark->getUsersBookmarks();
        $content = new Model_Page();
        $this->view->pages = $content->getCurrentUsersPages('create_date DESC', 10);
        $user = new Model_User();
        $this->view->identity = $user->getCurrentUser();
    }

    public function notesAction()
    {
        $notes = new Model_Note();
        $myNotes = DSF_Filter_Post::get('content');
        $notes->saveUsersNotes($myNotes);
        $this->_redirect('admin/index');
    }

    public function bookmarkAction()
    {
        $url = $this->_request->getParam('url');
        if ($this->_request->getParam('label')) {
            $label = $this->_request->getParam('label');
        } else {
            $label = $url;
        }
        $bookmark = new Model_Bookmark();
        $bookmark->addUsersBookmark($label, $url);
    }

    public function deleteBookmarkAction()
    {
        $id = $this->_request->getParam('id');
        $bookmark = new Model_Bookmark();
        $bookmark->deleteBookmark($id);
        $this->_redirect('admin/index');
    }

    public function testAction()
    {

    }
}