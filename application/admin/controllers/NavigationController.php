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
 * @see Digitalus_Controller_Action
 */
require_once 'Digitalus/Controller/Action.php';

/**
 * Admin Navigation Controller of Digitalus CMS
 *
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Controllers
 * @version     $Id: MenuController.php Mon Dec 24 20:53:11 EST 2007 20:53:11 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.0.0
 * @uses        Model_Menu
 * @uses        Model_Redirector
 */
class Admin_NavigationController extends Digitalus_Controller_Action
{
    /**
     * Initialize the action
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        //the selected admin menu item
        $this->view->adminSection = 'navigation';
        $this->view->breadcrumbs = array(
           $this->view->getTranslation('Navigation') => $this->baseUrl . '/admin/navigation'
        );
    }

    /**
     * The default action
     *
     * Display the main menu admin page
     *
     * @return void
     */
    public function indexAction()
    {
        //open the main menu by default
        $this->_forward('open');
    }

    /**
     * Open action
     *
     * Open the specified menu
     *
     * @param int $id
     * @return void
     */
    public function openAction()
    {
        $mdlMenu = new Model_Menu();
        $menuId = $this->_request->getParam('id', 0);

        $label = 'Root';
        if ($menuId > 0) {
            $label = $mdlMenu->getLabel($menuId);
        }

        //fetch the menu
        $this->view->menuId = $menuId;
        $this->view->menu   = $mdlMenu->openMenu($menuId, true);

        $this->view->toolbarLinks = array();
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->baseUrl . '/admin/index/bookmark'
            . '/url/admin_navigation_open_id_' . $menuId
            . '/label/' . $this->view->getTranslation('Navigation') . ':' . $label;
        $this->view->breadcrumbs[$this->view->getTranslation('Open Menu') . ': ' . $this->view->getTranslation($label)] = $this->baseUrl . '/admin/navigation/open/id/' . $menuId;
    }

    /**
     * Edit action
     *
     * Edit a menu
     *
     * @return void
     */
    public function editAction()
    {
        if ($this->_request->isPost()) {
            $mdlMenu = new Model_Menu();
            $ids = Digitalus_Filter_Post::raw('id');
            $visibility = Digitalus_Filter_Post::raw('show_on_menu');
            $mdlMenu->updateMenuItems($ids, $visibility);
            $menuId = Digitalus_Filter_Post::get('menuId');
            $url = 'admin/navigation/open/id/' . $menuId;
            $this->_redirect($url);
        }
    }

    /**
     * Redirector action
     *
     * @return void
     */
    public function redirectorAction()
    {
        $r = new Model_Redirector();
        if ($this->_request->isPost()) {
            $request      = Digitalus_Filter_Post::raw('request');
            $response     = Digitalus_Filter_Post::raw('response');
            $responseCode = Digitalus_Filter_Post::raw('response_code');
            $r->setFromArray($request, $response, $responseCode);
        }
        $this->view->redirectors = $r->fetchAll();
    }
}