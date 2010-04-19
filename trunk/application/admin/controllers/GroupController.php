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
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: GroupController.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10.0
 */

/**
 * @see Digitalus_Controller_Action
 */
require_once 'Digitalus/Controller/Action.php';

/**
 * Admin Group Controller of Digitalus CMS
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Controllers
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10.0
 * @uses        Admin_Form_Group
 * @uses        Model_Group
 */
class Admin_GroupController extends Digitalus_Controller_Action
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
     * Render the group management interface
     *
     * @return void
     */
    public function indexAction()
    {
        $this->_redirect('admin/group/create');
    }

    /**
     * Open action
     *
     * Open a group for editing
     *
     * @return void
     */
    public function openAction()
    {
        $groupName = $this->_request->getParam('groupname');
        $form     = new Admin_Form_Group();
        $form->setAction($this->baseUrl . '/admin/group/open/groupname/' . $groupName);
        $mdlGroup = new Model_Group();
        $mdlPage  = new Model_Page();
        $elmGroupName = $form->getElement('name');
        $elmGroupName->addValidators(array(
            array('GroupnameExists', true, array('exclude' => $groupName)),
        ));
        $form->setModel($mdlGroup);
        $form->populateFromModel($groupName);

        if ($this->_request->isPost() && $form->isValid($_POST)) {
            $form->setModel($mdlGroup);
            //update the groups permissions
            $resources = Digitalus_Filter_Post::raw('acl_resources');
            $groupName = Digitalus_Filter_Post::get('name');
            $mdlGroup->updateAclResources($groupName, $resources);
            $group = $form->update();
        }

        if (!empty($groupName) && '' != $groupName) {
            $row = $mdlGroup->find($groupName)->current();
            $this->view->group = $row;
            $this->view->groupPermissions = $mdlGroup->getAclResources($row->name);

            $aclResources = $mdlGroup->getAclResources($row->name);

            if (Model_Group::GUEST_ROLE != $groupName) {
                $adminResources = $form->getElement('admin_resources');
                $adminResources->setValue($this->view->renderAclList('admin', $mdlGroup->getAclResources($row->name)));
            } else {
                $displayGroup = $form->getDisplayGroup('adminAclGroup');
                $displayGroup->addAttribs(array('style' => 'display: none;'));
            }
            $moduleResources = $form->getElement('module_resources');
            $moduleResources->setValue($this->view->renderAclList('module', $mdlGroup->getAclResources($row->name)));
            $publicResources = $form->getElement('public_resources');
// TODO: refactor
            $values = array();
            if (!empty($aclResources)) {
                foreach ($aclResources as $key => $value) {
                    if ('1' === $value) {
                        $values[] = $key;
                    }
                }
            }
            $publicResources->setValue($this->view->renderFileChecklist($values, 0, null, 'acl_resources', 'page_white_text'));

            // remove current group form parents list
            $form->getElement('parent')->removeMultiOption($groupName);
        }
        $this->view->form = $form;

        $breadcrumbLabel = $this->view->getTranslation('Open Group') . ': ' . $groupName;
        $this->view->breadcrumbs[$breadcrumbLabel] = $this->baseUrl . '/admin/group/open/groupname/' . $groupName;
        $this->view->toolbarLinks = array();
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->baseUrl . '/admin/index/bookmark'
            . '/url/admin_group_open_groupname_' . $groupName
            . '/label/' . $this->view->getTranslation('Group') . ':' . $groupName;
        $this->view->toolbarLinks['Delete'] = $this->baseUrl . '/admin/group/delete/groupname/' . $groupName;
    }

    /**
     * Add action
     *
     * Add a new group
     *
     * @return void
     */
    public function createAction()
    {
        $form = new Admin_Form_Group();
        $mdlGroup = new Model_Group();
        $form->setModel($mdlGroup);
        $form->setAction($this->baseUrl . '/admin/group/create');
        $form->removeElement('admin_resources');
        $form->removeElement('module_resources');
        $form->removeElement('public_resources');
        $form->removeElement('update_permissions');
        $form->removeDisplayGroup('adminAclGroup');
        $form->removeDisplayGroup('moduleAclGroup');
        $form->removeDisplayGroup('publicAclGroup');

        if ($this->_request->isPost() && $form->isValid($_POST)) {
            $values = $form->getValues();

            $result = $mdlGroup->createGroup($values['name'], $values['parent'], $values['description']);
            if ($result) {
                $this->_redirect('admin/group/open/groupname/' . $values['name']);
            }
        }
        $this->view->form = $form;

        $this->view->breadcrumbs['Create Group'] = $this->baseUrl . '/admin/group/create';
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->baseUrl . '/admin/index/bookmark/url/admin_group_create';
    }

    /**
     * Copy ACL action
     *
     * @return void
     */
    public function copyAclAction()
    {
        $currentGroup = Digitalus_Filter_Post::get('name');
        $copyFrom     = Digitalus_Filter_Post::get('from_groupname');

        if (!empty($currentGroup) && !empty($copyFrom)) {
            $mdlGroup = new Model_Group();
            $mdlGroup->copyPermissions($copyFrom, $currentGroup);
        }
        $url = 'admin/group/open/groupname/' . $currentGroup;
        $this->_redirect($url);
    }

    /**
     * Delete action
     *
     * Delete a group
     *
     * @return void
     */
    public function deleteAction()
    {
        $groupName = $this->_request->getParam('groupname');
        $mdlGroup = new Model_Group();
// TODO: display error message or warning
        if (Model_Group::SUPERUSER_ROLE != $groupName && Model_Group::GUEST_ROLE != $groupName) {
            $mdlGroup->delete("name = '$groupName'");
        }
        $url = 'admin/site';
        $this->_redirect($url);
    }

}