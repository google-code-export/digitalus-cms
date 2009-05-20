<?php
/**
 * Digitalus CMS
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
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id:$
 * @link       http://www.digitaluscms.com
 * @since      Release 1.0.0
 */

/** Zend_Controller_Action */
require_once 'Zend/Controller/Action.php';

/**
 * Admin Index Conroller of Digitalus CMS
 *
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @category   Digitalus CMS
 * @package    Digitalus_CMS_Controllers
 * @version    $Id: IndexController.php Mon Dec 24 20:50:29 EST 2007 20:50:29 forrest lyman $
 * @link       http://www.digitaluscms.com
 * @since      Release 1.0.0
 */
class Admin_IndexController extends Zend_Controller_Action
{

    /**
     * Initialize the action
     *
     * @return void
     */
    public function init()
    {
        $this->view->breadcrumbs = array(
           $this->view->getTranslation('Home') => $this->getFrontController()->getBaseUrl() . '/admin'
        );
    }

    /**
     * The default action
     *
     * Displays the admin dashboard
     *
     * @return void
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

    /**
     * Notes action
     *
     * @return void
     */
    public function notesAction()
    {
        $notes = new Model_Note();
        $myNotes = Digitalus_Filter_Post::get('content');
        $notes->saveUsersNotes($myNotes);
        $this->_redirect('admin/index');
    }

    /**
     * Bookmark action
     *
     * @return void
     */
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

    /**
     * Delete bookmark action
     *
     * @return void
     */
    public function deleteBookmarkAction()
    {
        $id = $this->_request->getParam('id');
        $bookmark = new Model_Bookmark();
        $bookmark->deleteBookmark($id);
        $this->_redirect('admin/index');
    }

    /**
     * Test action
     *
     * @return void
     */
    public function testAction()
    {

    }
}