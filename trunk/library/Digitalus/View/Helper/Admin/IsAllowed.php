<?php
/**
 * IsAllowed helper
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
 * @category    Digitalus
 * @package     Digitalus_View
 * @subpackage  Helper
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id:$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * IsAllowed helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */
class  Digitalus_View_Helper_Admin_IsAllowed extends Zend_View_Helper_Abstract
{
    /**
     * comments
     */
    public function isAllowed($module, $controller = null, $action = null)
    {
        $acl     = new Digitalus_Acl();
        $mdlUser = new model_User();
        $user    = $mdlUser->getCurrentUser();
        //go from more specific to less specific
        $moduleLevel     = $module;
        $controllerLevel = $moduleLevel . '_' . $controller;
        $actionLevel     = $controllerLevel . '_' . $action;

        if (null != $action && $acl->has($actionLevel)) {
            $resource = $actionLevel;
        } else if (null != $controller && $acl->has($controllerLevel)) {
            $resource = $controllerLevel;
        } else {
            $resource = $moduleLevel;
        }
        if ($acl->has($resource)) {
            if ($acl->isAllowed($user->role, $resource)) {
                return true;
            }
        }
        return false;
    }
}