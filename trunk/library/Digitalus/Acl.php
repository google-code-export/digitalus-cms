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
 * @category    Digitalus CMS
 * @package     Digitalus
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id$
 */

class Digitalus_Acl extends Zend_Acl
{

    protected $_resourceList; //the resources list is the public array and is not used internally

    /**
     * load the acl resources and set up permissions
     *
     */
    public function __construct()
    {
        $this->_addRoles();

        $this->loadResources();
        $this->loadCurrentUsersPermissions();

        //deny the guests access to everything
        $this->deny(Model_Group::GUEST_ROLE);

        //grant the super admin access to everything
        $this->allow(Model_Group::SUPERUSER_ROLE);

        //load common resources
        $this->addResource(new Zend_Acl_Resource('admin_auth'));
        //everybody
        $this->allow(null, 'admin_auth');
    }

    public function loadResources()
    {
        $front = Zend_Controller_Front::getInstance();
        $ctlPaths = $front->getControllerDirectory();

        //set the path to all of the modules
        foreach ($ctlPaths as $module => $path)  {
            if ($module != 'public' && $module != 'front') {
                //clear the resource list items
                $resourceListItems = null;

                $path = str_replace('controllers', 'acl.xml', $path);

                //load the module resource
                $this->addResource(new Zend_Acl_Resource($module));

                //attempt to load each acl file
                if (file_exists($path)) {
                    if ($xml = @simplexml_load_file($path)) {
                        $controllers = $xml->children();
                        foreach ($controllers as $controller) {
                            $controllerName = (string)$controller->attributes()->name;
                            $controllerActions = $controller->children();
                            if (count($controllerActions) > 0) {
                                foreach ($controllerActions as $action) {
                                    //load each action separately
                                    $actionName = (string)$action;
                                    $key = $module . '_' . $controllerName . '_' . $actionName;
                                    $this->addResource(new Zend_Acl_Resource($key), $module);

                                    //add the action to the public resource list
                                    $resourceListItems[$controllerName][] = $actionName;
                                }
                            } else {
                                //set the resource at the controller level
                                $key = $module . '_' . $controllerName;
                                $this->addResource(new Zend_Acl_Resource($key), $module);

                                //add the controller to the public resource list
                                $resourceListItems[$controllerName] = null;
                            }
                        }
                    } else {
                        throw new Digitalus_Acl_Exception('xml file is not valid: ' . $path);
                    }
                }

                $this->_resourceList[$module] = $resourceListItems;
            } else {
                //load the module resource
                $this->addResource(new Zend_Acl_Resource($module));

                $mdlPage   = new Model_Page();
                $pageNames = $mdlPage->getPageNamesArray();
                foreach ($pageNames as $pageName) {
                    $pageName = strtolower(Digitalus_Toolbox_String::replaceEmptySpace($pageName));
                    $this->_resourceList[$pageName] = $pageName;
                    $this->addResource(new Zend_Acl_Resource($pageName), $module);
                }
            }
        }
    }

    public function getResourceList()
    {
        return $this->_resourceList;
    }

    public function loadCurrentUsersPermissions()
    {
        $mdlUser = new Model_User();
        $user    = $mdlUser->getCurrentUser();
        $group   = $mdlUser->getGroupByUsername($user->name);
        $permissions = $mdlUser->getCurrentUsersAclResources();
        if (Model_Group::SUPERUSER_ROLE != $group && !empty($permissions)) {
            foreach ($permissions as $key => $value) {
                if ($this->has($key)) {
                    if ($value == 1) {
                        $this->allow($group, $key);
                    } else {
                        $this->deny($group, $key);
                    }
                }
            }
        }
    }

    private function _getResourceFromRow($row)
    {
        if ($row->admin_section == 'module') {
            return 'mod_' . $row->controller;
        } else {
            return $row->controller;
        }
    }

    /**
     * adds roles dynamically from database
     */
    private function _addRoles()
    {
        // add role 'guest' and 'superadmin' explicitly
        $this->addRole(new Zend_Acl_Role(Model_Group::GUEST_ROLE));
        $this->addRole(new Zend_Acl_Role(Model_Group::SUPERUSER_ROLE));

        // add roles dynamically from database
        $mdlGroup = new Model_Group();
        $groups = $mdlGroup->getGroupNamesParentsArray();
        foreach ($groups as $group) {
            switch (strtolower($group['name'])) {
                case Model_Group::GUEST_ROLE:
                case Model_Group::SUPERUSER_ROLE:
                    break;
                default:
                    $this->addRole(new Zend_Acl_Role($group['name'], $group['parent']));
                    break;
            }
        }
    }
}