<?php
/**
 * RenderAdminMenu helper
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
 * @version     $Id: RenderAdminMenu.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * RenderAdminMenu helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper Digitalus_View_Helper_GetBaseUrl
 * @uses        viewHelper Digitalus_View_Helper_GetRequest
 */
class Digitalus_View_Helper_Admin_RenderAdminMenu extends Zend_View_Helper_Abstract
{
    public $sections = array(
        'index'      => 'index',
        'group'      => 'site',
        'report'     => 'site',
        'site'       => 'site',
        'user'       => 'site',
        'page'       => 'page',
        'navigation' => 'navigation',
        'media'      => 'media',
        'module'     => 'module'
    );
    public $defaultSection = 'index';
    public $moduleSection  = 'module';
    public $selectedSection;

    public $userModel;
    public $currentUser;

    public function renderAdminMenu($selectedItem = null, $id = 'adminMenu')
    {
        $this->userModel = new Model_User();
        $this->currentUser = $this->userModel->getCurrentUser();

        $this->setSelectedSection();

        $menu = '<ul id="' . $id . '">';

        if (!$this->currentUser) {
            $menu .= '<li class="med"><a href="' . $this->view->getBaseUrl() . '/admin/auth/login" id="loginLink" class="selected">' . $this->view->getTranslation('Login') . '</a></li>';
        } else {
            if ($this->hasAccess('admin_index')) {
                $menu .= '<li class="small"><a href="' . $this->view->getBaseUrl() . '/admin" id="homeLink"' . $this->isSelected('index') . '>' . $this->view->getTranslation('Home') . '</a></li>';
            }
            if ($this->hasAccess('admin_site')) {
                $menu .= '<li class="small"><a href="' . $this->view->getBaseUrl() . '/admin/site" id="siteLink"' . $this->isSelected('site') . '>' . $this->view->getTranslation('Site') . '</a></li>';
            }
            if ($this->hasAccess('admin_page')) {
                $menu .= '<li class="med"><a href="' . $this->view->getBaseUrl() . '/admin/page" id="pageLink"' . $this->isSelected('page') . '>' . $this->view->getTranslation('Pages') . '</a></li>';
            }
            if ($this->hasAccess('admin_navigation')) {
                $menu .= '<li class="large"><a href="' . $this->view->getBaseUrl() . '/admin/navigation" id="navigationLink"' . $this->isSelected('navigation') . '>' . $this->view->getTranslation('Navigation') . '</a></li>';
            }
            if ($this->hasAccess('admin_media')) {
                $menu .= '<li class="med"><a href="' . $this->view->getBaseUrl() . '/admin/media" id="mediaLink"' . $this->isSelected('media') . '>' . $this->view->getTranslation('Media') . '</a></li>';
            }
            if ($this->hasAccess('admin_module')) {
                $menu .= '<li class="med"><a href="' . $this->view->getBaseUrl() . '/admin/module" id="moduleLink"' . $this->isSelected('module') . '>' . $this->view->getTranslation('Modules') . '</a></li>';
            }
        }
        $menu .= '</ul>';

        return $menu;
    }

    public function isSelected($tab)
    {
        if ($tab == $this->selectedSection) {
            return ' class="selected"';
        }
    }

    public function setSelectedSection()
    {
        $request = $this->view->getRequest();

        $module = $request->getModuleName();
        if (substr($module, 0, 4) == 'mod_') {
            $this->selectedSection = $this->moduleSection;
        } else {
            $controller = $request->getControllerName();
            if (isset($this->sections[$controller])) {
                $this->selectedSection = $this->sections[$controller];
            } else {
                $this->selectedSection = $this->defaultSection;
            }
        }
    }

    public function hasAccess($tab)
    {
        if ($this->currentUser) {
            if ($this->currentUser->role == Model_User::SUPERUSER_ROLE) {
                return true;
            } else if ($this->userModel->queryPermissions($tab)) {
                return true;
            }
        }
    }
}