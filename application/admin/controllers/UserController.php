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
 * @version     $Id: UserController.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.0.0
 */

/**
 * @see Digitalus_Controller_Action
 */
require_once 'Digitalus/Controller/Action.php';

/**
 * Admin User Controller of Digitalus CMS
 *
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Controllers
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.0.0
 * @uses        Admin_Form_User

 * @uses        Model_User
 */
class Admin_UserController extends Digitalus_Controller_Action
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
           $this->view->getTranslation('Site Settings') => $this->baseUrl . '/admin/site'
        );
    }

    /**
     * The default action
     *
     * Render the user management interface
     *
     * @return void
     */
    public function indexAction()
    {
        $this->_redirect('admin/user/create');
    }

    /**
     * Open action
     *
     * Open a user for editing
     *
     * @return void
     */
    public function openAction()
    {
        $userName = $this->_request->getParam('username');
        $form = new Admin_Form_User();
        $u = new Model_User();
        $elmUserName = $form->getElement('name');
        $elmUserName->addValidators(array(
            array('UsernameExists', true, array('exclude' => $userName)),
        ));
        $form->removeElement('update_password');
        $form->removeElement('password');
        $form->removeElement('password_confirm');
        $form->removeElement('captcha');
        $form->setModel($u);
        $form->populateFromModel($userName);
        $form->setAttrib('id', 'general');
        $submit = $form->getElement('submitAdminUserForm');
        $submit->setAttribs(array('id' => 'update', 'name' => 'update'));
        $submit->setLabel($this->view->getTranslation('Update Account'));
        $form->setAction($this->baseUrl . '/admin/user/open/username/' . $userName);

        if ($this->_request->isPost() && $form->isValid($_POST)) {
            $form->setModel($u);
            if (Digitalus_Filter_Post::has('admin_user_password')) {
                $userName = Digitalus_Filter_Post::get('username');
                $password = Digitalus_Filter_Post::get('newPassword');
                $passwordConfirm = Digitalus_Filter_Post::get('newConfirmPassword');
                $u->updatePassword($userName, $password, true, $passwordConfirm);
            }
            $user = $form->update();
        }
        $this->view->userName = $userName;
        $this->view->form     = $form;

        $breadcrumbLabel = $this->view->getTranslation('Open User') . ': ' . $userName;
        $this->view->breadcrumbs[$breadcrumbLabel] = $this->baseUrl . '/admin/user/open/username/' . $userName;
        $this->view->toolbarLinks = array();
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->baseUrl . '/admin/index/bookmark'
            . '/url/admin_user_open_username_' . $userName
            . '/label/' . $this->view->getTranslation('User') . ':' . $userName;
        $this->view->toolbarLinks['Delete'] = $this->baseUrl . '/admin/user/delete/username/' . $userName;
    }

    /**
     * Add action
     *
     * Add a new user
     *
     * @return void
     */
    public function createAction()
    {
        $form = new Admin_Form_User();
        $form->removeElement('update_password');
        $form->removeElement('captcha');
        $u = new Model_User();
        $form->setModel($u);
        if ($form->validatePost()) {
            $password = $form->getValue('password');
            $userName = $form->getValue('name');
            $result   = $form->create(array('password' => md5($password)));
            if ($result) {
                $this->_redirect('admin/user/open/username/' . $userName);
            }
        }
        $this->view->breadcrumbs['Create User'] = $this->baseUrl . '/admin/user/create';
        $form->setAction($this->baseUrl . '/admin/user/create');
        $this->view->form = $form;
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->baseUrl . '/admin/index/bookmark/url/admin_user_create';
    }

    /**
     * Update my account action
     *
     * @return void
     */
    public function updateMyAccountAction()
    {
        $u = new Model_User();
        $user = $u->getCurrentUser();
        $user->first_name = Digitalus_Filter_Post::get('first_name');
        $user->last_name  = Digitalus_Filter_Post::get('last_name');
        $user->email      = Digitalus_Filter_Post::get('email');
        $user->save();

        if (Digitalus_Filter_Post::int('update_password') === 1) {
            $password        = Digitalus_Filter_Post::get('password');
            $passwordConfirm = Digitalus_Filter_Post::get('password_confirm');
            $u->updatePassword($user->name, $password, true, $passwordConfirm);
        }
        $url = 'admin/index';
        $this->_redirect($url);
    }

    /**
     * Delete action
     *
     * Delete a user
     *
     * @return void
     */
    public function deleteAction()
    {
       $userName = $this->_request->getParam('username');
       $u = new Model_User();
       $u->delete("name = '$userName'");
       $url = 'admin/site';
       $this->_redirect($url);
    }

}