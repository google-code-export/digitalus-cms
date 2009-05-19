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
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id:$
 * @link       http://www.digitaluscms.com
 * @since      Release 1.0.0
 */

/** Zend_Controller_Action */
require_once 'Zend/Controller/Action.php';

/**
 * Admin User Conroller of Digitalus CMS
 *
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @category   DSF CMS
 * @package    DSF_CMS_Controllers
 * @version    $Id: UserController.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
 * @link       http://www.digitaluscms.com
 * @since      Release 1.0.0
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
        $id = $this->_request->getParam('id', 0);
        if ($id > 0) {
            $u = new Model_User();
            $row = $u->find($id)->current();
            $this->view->user = $row;
            $this->view->userPermissions = $u->getAclResources($row);
        }

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
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            $u = new Model_User();
            $user = $u->insertFromPost();
            $e = new DSF_View_Error();
            if (!$e->hasErrors()) {
                $url = 'admin/user/open/id/' . $user->id;
                $this->_redirect($url);
            } else {
                $storage = new DSF_Data_Storage();
                $storage->savePost();
            }
        }
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->getFrontController()->getBaseUrl() . '/admin/index/bookmark/url/admin_user_create';
    }

    /**
     * Edit action
     *
     * Edit an existing user
     *
     * @return void
     */
    public function editAction()
    {
        $u = new Model_User();
        if (DSF_Filter_Post::has('update_permissions')) {
            //update the users permissions
            $resources = DSF_Filter_Post::raw('acl_resources');
            $id = DSF_Filter_Post::int('id');
            $u->updateAclResources($id, $resources);
        } elseif (DSF_Filter_Post::has('admin_user_password')) {
            $id = DSF_Filter_Post::int('id');
            $password = DSF_Filter_Post::get('newPassword');
            $passwordConfirm = DSF_Filter_Post::get('newConfirmPassword');
            $u->updatePassword($id, $password, true, $passwordConfirm);
        } else {
            $user = $u->updateFromPost();
            $id = $user->id;
        }
           $url = 'admin/user/open/id/' . $id;

      $this->_redirect($url);

    }

    /**
     * Update my account action
     *
     * @return void
     */
    public function updateMyAccountAction()
    {
        $u = new Model_User();
        if (DSF_Filter_Post::int('update_password') === 1) {
            $id = DSF_Filter_Post::int('id');
            $password = DSF_Filter_Post::get('password');
            $passwordConfirm = DSF_Filter_Post::get('confirmation');
            $u->updatePassword($id, $password, true, $passwordConfirm);
        }

        $user = $u->updateFromPost();
        $id = $user->id;

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
        $currentUser = DSF_Filter_Post::int('id');
        $copyFrom = DSF_Filter_Post::int('user_id');

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