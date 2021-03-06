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
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id:$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.0.0
 */

/**
 * @see Digitalus_Controller_Action
 */
require_once 'Digitalus/Controller/Action.php';

/**
 * Admin Index Controller of Digitalus CMS
 *
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Controllers
 * @version     $Id: IndexController.php Mon Dec 24 20:50:29 EST 2007 20:50:29 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.0.0
 * @uses        Model_Note
 * @uses        Model_Bookmark
 * @uses        Model_Page
 * @uses        Model_User
 */
class Admin_IndexController extends Digitalus_Controller_Action
{
    /**
     * Initialize the action
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        $this->view->breadcrumbs = array(
           $this->view->getTranslation('Home') => $this->baseUrl . '/admin'
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
        $content = new Model_Page();
        $this->view->pages = $content->getCurrentUsersPages('create_date DESC', 5);
        $user = new Model_User();
        $identity = $user->getCurrentUser();

        $form = new Admin_Form_User();
        $form->onlyIndexIndexActionElements();
        $form->setAction($this->baseUrl . '/admin/user/update-my-account');
        $firstName = $form->getElement('first_name');
        $firstName->setValue($identity->first_name);
        $lastName = $form->getElement('last_name');
        $lastName->setValue($identity->last_name);
        $email = $form->getElement('email');
        $email->setValue($identity->email);
        $submit = $form->getElement('submitAdminUserForm');
        $submit->setLabel($this->view->getTranslation('Update My Account'));
        $displayGroup = $form->getDisplayGroup('adminUserGroup');
        $displayGroup->setLegend($this->view->getTranslation('My Account'))
                     ->setAttrib('class', 'formColumn');
        $this->view->form = $form;
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
        $url   = $this->_request->getParam('url');
        $label = $this->_request->getParam('label', $url);
        // the bookmark links are set up so if you dont have js enabled it will just use a default value for you
        // this makes it pass an array as the label if you do set it, so we need to fetch the last item if it is an array
        if (is_array($label)) {
            $label = array_pop($label);
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