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
 * @author      Forrest Lyman
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_Controller
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: Auth.php 729 2010-04-19 20:11:57Z lowtower@gmx.de $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.8.0
 */

/** Zend_Controller_Plugin_Abstract */
require_once 'Zend/Controller/Plugin/Abstract.php';

/**
 * Auth Plugin
 *
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.8.0
 */
class Digitalus_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    /**
     * the current user's identity
     *
     * @var Zend_Db_Row
     */
    private $_identity;

    /**
     * the acl object
     *
     * @var Zend_Acl
     */
    private $_acl;

    /**
     * the page to direct to if there is a current
     * user but they do not have permission to access
     * the resource
     *
     * @var array
     */
    private $_noAcl = array(
        'admin' => array(
            'module'     => 'admin',
            'controller' => 'error',
            'action'     => 'no-auth'
        ),
        'public' => array(
            'module'     => 'public',
            'controller' => 'index',
            'action'     => 'login'
        ),
    );

    /**
     * the page to direct to if there is no current user
     *
     * @var array
     */
    private $_noAuth = array(
        'admin' => array(
            'module'     => 'admin',
            'controller' => 'auth',
            'action'     => 'login'
        ),
    );

    /**
     * validate the current user's request
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $this->_identity = Digitalus_Auth::getIdentity();
        $this->_acl      = new Digitalus_Acl();

        $role = Model_Group::GUEST_ROLE;
        if (!empty($this->_identity)) {
            $role = $this->_identity->role;
        }

        $module     = $request->module;
        $controller = $request->controller;
        $action     = $request->action;

        if ($module != 'public' && $controller != 'public') {
            //go from more specific to less specific
            $moduleLevel     = $module;
            $controllerLevel = $moduleLevel . '_' . $controller;
            $actionLevel     = $controllerLevel . '_' . $action;

            if ($this->_acl->has($actionLevel)) {
                $resource = $actionLevel;
            } else if ($this->_acl->has($controllerLevel)) {
                $resource = $controllerLevel;
            } else {
                $resource = $moduleLevel;
            }

            if ($this->_acl->has($resource) && !$this->_acl->isAllowed($role, $resource)) {
                if (!$this->_identity || Model_Group::GUEST_ROLE == $role) {
                    $request->setModuleName($this->_noAuth['admin']['module']);
                    $request->setControllerName($this->_noAuth['admin']['controller']);
                    $request->setActionName($this->_noAuth['admin']['action']);
                    $request->setParam('authPage', 'login');
                } else {
                   $request->setModuleName($this->_noAcl['admin']['module']);
                   $request->setControllerName($this->_noAcl['admin']['controller']);
                   $request->setActionName($this->_noAcl['admin']['action']);
                   $request->setParam('authPage', 'noauth');
               }
            }
        } else {
            $resource = Digitalus_Toolbox_Page::getCurrentPageName();
            // write pageName to registry when coming from a page
            if ('index' == $controller && 'index' == $action) {
                Zend_Registry::set('Digitalus_Page_Name', $resource);
            }
// TODO: refactor into Toolbox String - replace underscores with empty spaces
            $resource = strtolower(str_replace('_', ' ', $resource));
            // only check Acl if page is NOT homepage
            if (!empty($resource) && '' != $resource &&
                    Digitalus_Toolbox_Page::getHomePageName() != $resource) {
                if ($this->_acl->has($resource) && !$this->_acl->isAllowed($role, $resource)) {
                    if (!$this->_identity || Model_Group::GUEST_ROLE != $role) {
                        $request->setModuleName($this->_noAcl['public']['module']);
                        $request->setControllerName($this->_noAcl['public']['controller']);
                        $request->setActionName($this->_noAcl['public']['action']);
                        $request->setParam('authPage', 'login');
                    } else {
                        $request->setModuleName($this->_noAcl['public']['module']);
                        $request->setControllerName($this->_noAcl['public']['controller']);
                        $request->setActionName($this->_noAcl['public']['action']);
                        $request->setParam('authPage', 'noauth');
                    }
                }
            }
        }
    }
}