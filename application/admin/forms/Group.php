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
 * @version     $Id: Group.php 701 2010-03-05 16:23:59Z lowtower@gmx.de $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10.0
 */

/**
 * @see Digitalus_Form
 */
require_once 'Digitalus/Form.php';

/**
 * Admin User Group Form
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Admin
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10.0
 * @uses        Model_Group
 */
class Admin_Form_Group extends Digitalus_Form
{
    /**
     * Initialize the form
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        $view = $this->getView();

        // create new element
        $groupName = $this->createElement('text', 'name', array(
            'label'         => $view->getTranslation('Group name'),
            'required'      => true,
            'filters'       => array('StringTrim', 'StripTags'),
            'validators'    => array(
                array('NotEmpty', true),
                array('StringLength', true, array(4, Model_Group::GROUPNAME_LENGTH)),
                array('Regex', true, array(
                    'pattern'  => Model_Group::GROUPNAME_REGEX,
                    'messages' => array('regexNotMatch' => Model_Group::GROUPNAME_REGEX_NOTMATCH),
                )),
            ),
            'attribs'       => array('size' => 40),
        ));

        // create new element
        $groupParent = $view->selectGroup('parent', null, null, null, 'superadmin');
        $groupParent->setOptions(array(
            'label'         => $view->getTranslation('Parent group'),
        ));

        // create new element
        $groupLabel = $this->createElement('text', 'label', array(
            'label'         => $view->getTranslation('Group label'),
            'filters'       => array('StringTrim', 'StripTags'),
            'validators'    => array(
                array('StringLength', true, array(0, 30)),
            ),
            'attribs'       => array('cols' => 32, 'rows' => 5),
        ));

        // create new element
        $groupDescription = $this->createElement('textarea', 'description', array(
            'label'         => $view->getTranslation('Group description'),
            'filters'       => array('StringTrim', 'StripTags'),
            'validators'    => array(
                array('StringLength', true, array(0, 200)),
            ),
            'attribs'       => array('cols' => 32, 'rows' => 5),
        ));

        $adminList = $this->createElement('AnyMarkup', 'admin_resources', array(
            'decorators'    => array('ViewHelper'),
            'belongsTo'     => 'acl_resources',
        ));

        $moduleList = $this->createElement('AnyMarkup', 'module_resources', array(
            'decorators'    => array('ViewHelper'),
            'belongsTo'     => 'acl_resources',
        ));

        $publicList = $this->createElement('AnyMarkup', 'public_resources', array(
            'decorators'    => array('ViewHelper'),
            'belongsTo'     => 'acl_resources',
        ));

        $submitPermissions = $this->createElement('submit', 'update_permissions', array(
            'label'         => $view->getTranslation('Update Permissions'),
            'decorators'    => array('ViewHelper'),
            'attribs'       => array('class' => 'submit'),
        ));

        $submit = $this->createElement('submit', 'submitAdminGroupForm', array(
            'label'         => $view->getTranslation('Submit'),
            'attribs'       => array('class' => 'submit'),
        ));

        // add the elements to the form
        $this->addElement($groupName)
             ->addElement($groupParent)
             ->addElement($groupLabel)
             ->addElement($groupDescription)
             ->addElement($adminList)
             ->addElement($moduleList)
             ->addElement($publicList)
             ->addElement($submitPermissions)
             ->addElement($submit)
             ->setAttribs(array('id' => 'permissions', 'class' => 'padding-10'))
             ->addDisplayGroup(array('form_instance', 'name', 'parent', 'label', 'description', 'submitAdminGroupForm'),
                 'generalGroup',
                 array('legend' => $view->getTranslation('User Group'), 'id' => 'general')
             )
             ->addDisplayGroup(array('admin_resources', 'update_permissions'),
                 'adminAclGroup',
                 array('legend' => $view->getTranslation('Admin Permissions'), 'id' => 'admin_permissions')
             )
             ->addDisplayGroup(array('module_resources', 'update_permissions'),
                 'moduleAclGroup',
                 array('legend' => $view->getTranslation('Module Permissions'), 'id' => 'module_permissions')
             )
             ->addDisplayGroup(array('public_resources', 'update_permissions'),
                 'publicAclGroup',
                 array('legend' => $view->getTranslation('Public Permissions'), 'id' => 'public_permissions')
             );
    }

    public function onlyCreateActionElements()
    {
        $this->removeElement('admin_resources');
        $this->removeElement('module_resources');
        $this->removeElement('public_resources');
        $this->removeElement('update_permissions');
        $this->removeDisplayGroup('adminAclGroup');
        $this->removeDisplayGroup('moduleAclGroup');
        $this->removeDisplayGroup('publicAclGroup');
    }
}