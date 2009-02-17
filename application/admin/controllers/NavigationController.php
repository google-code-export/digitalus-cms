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
 * @category   DSF CMS
 * @package    DSF_CMS_Controllers
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: MenuController.php Mon Dec 24 20:53:11 EST 2007 20:53:11 forrest lyman $
 */

class Admin_NavigationController extends Zend_Controller_Action
{
    public function init()
    {
        //the selected admin menu item
        $this->view->adminSection = 'navigation';
        $this->view->breadcrumbs = array(
           $this->view->GetTranslation('Navigation') => $this->getFrontController()->getBaseUrl() . '/admin/navigation'
        );

    }

    /**
     * display the main menu admin page
     *
     */
    public function indexAction()
    {
        //open the main menu by default
        $this->_forward('open');
    }

    /**
     * open the specified menu
     *
     * @param int $id
     */
    public function openAction()
    {
        $mdlMenu = new Menu();
        $menuId = $this->_request->getParam('id', 0);

        if ($menuId > 0) {
            $label = $mdlMenu->getLabel($menuId);
        } else {
            $label = 'Root';
        }

        $this->view->toolbarLinks = array();
        $this->view->toolbarLinks[$this->view->GetTranslation('Add to my bookmarks')] = $this->getFrontController()->getBaseUrl() . '/admin/index/bookmark'
            . '/url/admin_navigation_open_id_' . $menuId
            . '/label/' . $this->view->GetTranslation('Navigation') . ':' . $label;

        $this->view->breadcrumbs[$this->view->GetTranslation('Open Menu') . ': ' . $this->view->GetTranslation($label)] =   $this->getFrontController()->getBaseUrl() . '/admin/navigation/open/id/' . $menuId;

        //fetch the menu
        $this->view->menuId = $menuId;
        $this->view->menu = $mdlMenu->openMenu($menuId, true);
    }

    /**
     * edit a menu
     *
     */
    public function editAction()
    {
        if ($this->_request->isPost()) {
            $m = new Menu();
            $ids = DSF_Filter_Post::raw('id');
            $labels = DSF_Filter_Post::raw('label');
            $visibility = DSF_Filter_Post::raw('show_on_menu');
            $m->updateMenuItems($ids, $labels, $visibility);
            $menuId = DSF_Filter_Post::get('menuId');
            $url = 'admin/navigation/open/id/' . $menuId;
            $this->_redirect($url);
        }
    }

    public function redirectorAction()
    {
        $r = new Redirector();
        if ($this->_request->isPost()) {
            $request = DSF_Filter_Post::raw('request');
            $response = DSF_Filter_Post::raw('response');
            $responseCode = DSF_Filter_Post::raw('response_code');
            $r->setFromArray($request, $response, $responseCode);
        }
        $this->view->redirectors = $r->fetchAll();
    }
}