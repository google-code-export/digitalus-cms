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
 * @see Zend_Controller_Action
 */
require_once 'Zend/Controller/Action.php';

/**
 * Admin User Controller of Digitalus CMS
 *
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Controllers
 * @version     $Id: UserController.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.0.0
 * @uses        Admin_Form_User
 * @uses        Model_User
 */
class Admin_UserController extends Zend_Controller_Action
{
    /**
     * Initialize the action
     *
     * @return void
     */
    public function init()
    {
        $this->view->breadcrumbs = array(
           $this->view->getTranslation('Site Settings') => $this->getFrontController()->getBaseUrl() . '/admin/site'
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
        $id = (int)$this->_request->getParam('id', 0);
        $form = new Admin_Form_User();
        $u = new Model_User();
        $exclude = $u->getUserById($id);
        $userName = $form->getElement('username');
        $userName->addValidators(array(
            array('UsernameExists', true, array('exclude' => $exclude->username)),
        ));
        $form->removeElement('password');
        $form->removeElement('password_confirm');
        $form->removeElement('captcha');
        $form->setModel($u);
        $form->populateFromModel($id);
        $form->setAttribs(array('id' => 'general'));
        $submit = $form->getElement('submitAdminUserForm');
        $submit->setAttribs(array('id' => 'update', 'name' => 'update'));
        $submit->setLabel($this->view->getTranslation('Update Account'));
        $form->setAction($this->getFrontController()->getBaseUrl() . '/admin/user/open/id/' . $id);

        if ($id > 0) {
            $row = $u->find($id)->current();
            $this->view->user = $row;
            $this->view->userPermissions = $u->getAclResources($row);
        }

        if ($this->_request->isPost()) {
            $form->setModel($u);
            if (Digitalus_Filter_Post::has('update_permissions')) {
                //update the users permissions
                $resources = Digitalus_Filter_Post::raw('acl_resources');
                $id = Digitalus_Filter_Post::int('id');
                $u->updateAclResources($id, $resources);
            } else if (Digitalus_Filter_Post::has('admin_user_password')) {
                $id = Digitalus_Filter_Post::int('id');
                $password = Digitalus_Filter_Post::get('newPassword');
                $passwordConfirm = Digitalus_Filter_Post::get('newConfirmPassword');
                $u->updatePassword($id, $password, true, $passwordConfirm);
            } else {
                if ($form->isValid($_POST)) {
                    $user = $form->update();
                    $id = $user->id;
                }
            }
        }
        $this->view->form = $form;

        $breadcrumbLabel = $this->view->getTranslation('Open User') . ': ' . $this->view->user->first_name . ' ' . $this->view->user->last_name;
        $this->view->breadcrumbs[$breadcrumbLabel] = $this->getFrontController()->getBaseUrl() . '/admin/user/open/id/' . $id;
        $this->view->toolbarLinks = array();
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->getFrontController()->getBaseUrl() . '/admin/index/bookmark'
            . '/url/admin_user_open_id_' . $id
            . '/label/' . $this->view->getTranslation('User') . ':' . $this->view->user->first_name . '.' . $this->view->user->last_name;
        $this->view->toolbarLinks['Delete'] = $this->getFrontController()->getBaseUrl() . '/admin/user/delete/id/' . $id;
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
        $form->removeElement('captcha');
        $userName = $form->getElement('username');
        $userName->addValidators(array(
            array('UsernameExists', true),
        ));
Zend_Debug::dump(Zend_Registry::get('Zend_Translate'));
        $u = new Model_User();
        $form->setModel($u);
        if ($form->validatePost()) {
            $password = $form->getValue('password');
            $result = $form->create(array('password' => md5($password)));
            if ($result) {
                $this->_redirect('admin/user/open/id/' . $result->id);
            }
        }
        $this->view->breadcrumbs['Create User'] = $this->getFrontController()->getBaseUrl() . '/admin/user/create';
        $form->setAction($this->getFrontController()->getBaseUrl() . '/admin/user/create');
        $this->view->form = $form;
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->getFrontController()->getBaseUrl() . '/admin/index/bookmark/url/admin_user_create';
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
        $user->last_name = Digitalus_Filter_Post::get('last_name');
        $user->email = Digitalus_Filter_Post::get('email');
        $user->save();

        if (Digitalus_Filter_Post::int('update_password') === 1) {
            $password = Digitalus_Filter_Post::get('password');
            $passwordConfirm = Digitalus_Filter_Post::get('confirmation');
            $u->updatePassword($user->id, $password, true, $passwordConfirm);
        }

        $url = 'admin/index';
        $this->_redirect($url);
    }

    /**
     * Copy ACL action
     *
     * @return void
     */
    public function copyAclAction()
    {
        $currentUser = Digitalus_Filter_Post::int('id');
        $copyFrom = Digitalus_Filter_Post::int('user_id');

        if ($currentUser > 0 && $copyFrom > 0) {
            $u = new Model_User();
            $u->copyPermissions($copyFrom, $currentUser);
        }
        $url = 'admin/user/open/id/' . $currentUser;
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
       $id = $this->_request->getParam('id');
       $u = new Model_User();
       $u->delete('id = ' . $id);
       $url = 'admin/site';
       $this->_redirect($url);
    }

}