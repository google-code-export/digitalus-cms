<?php
/**
 * RenderAclList helper
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
 * @author      Forrest Lyman
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_View
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: RenderAclList.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * RenderAclList helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */
class Digitalus_View_Helper_Admin_RenderAclList extends Zend_View_Helper_Abstract
{
    public function renderAclList($zfModule = 'admin', $usersPermissions = array(), $id = 'aclList')
    {
        $this->permissions = $usersPermissions;

        $acl = new Digitalus_Acl();
        $resources = $acl->getResourceList();

        $xhtml = '<ul id="' . $id . '">';

        foreach ($resources as $module => $resources) {
            if (strtolower($module) == strtolower($zfModule) || (
                'module' == strtolower($zfModule) && substr($module, 0, 4) == 'mod_')) {
                if (!is_array($resources)) {
                    $key = $module;
                    $xhtml .= '<li class="module">' . $this->view->formCheckbox("acl_resources[{$key}]", $this->hasPermision($key, $usersPermissions)) . $module;
                } else {
                    $xhtml .= '<li class="module">' . $module;
                    $xhtml .= '<ul>';
                    foreach ($resources as $controller => $actions) {
                        if (!is_array($actions)) {
                            $key = $module . '_' . $controller;
                            $xhtml .= '<li class="controller">' . $this->view->formCheckbox("acl_resources[{$key}]", $this->hasPermision($key, $usersPermissions)) . $controller;
                        } else {
                            $xhtml .= '<li class="controller">' . $controller;
                            $xhtml .= '<ul>';
                            foreach ($actions as $action) {
                                $key = $module . '_' . $controller . '_' . $action;
                                $xhtml .= '<li class="action">' . $this->view->formCheckbox("acl_resources[{$key}]", $this->hasPermision($key, $usersPermissions)) . $action . '</li>';
                            }
                            $xhtml .= '</ul>';
                        }
                        $xhtml .= '</li>'; //end of controller
                    }
                $xhtml .= '</ul>';
                }
                $xhtml .= '</li>'; //end of module
            }
        }
        $xhtml .= '</ul>';

        return $xhtml;

    }

    public function hasPermision($key, $userPermissions)
    {
        if (is_array($userPermissions) && isset($userPermissions[$key])) {
            $result = $userPermissions[$key];
            return intval($result);
        }
    }
}