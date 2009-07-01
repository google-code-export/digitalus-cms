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
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id:$
 * @link       http://www.digitaluscms.com
 * @since      Release 1.0.0
 */

/** Zend_Controller_Action */
require_once 'Zend/Controller/Action.php';

/**
 * Admin Design Conroller of Digitalus CMS
 *
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @category   Digitalus CMS
 * @package    Digitalus_CMS_Controllers
 * @version    $Id:
 * @link       http://www.digitaluscms.com
 * @since      Release 1.0.0
 */
class Admin_DesignController extends Zend_Controller_Action
{

    /**
     * Initialize the action
     *
     * @return void
     */
    public function init()
    {
        $this->view->breadcrumbs = array(
           $this->view->getTranslation('Design') => $this->getFrontController()->getBaseUrl() . '/admin/design'
        );
    }

    /**
     * The default action
     *
     * @return void
     */
    public function indexAction()
    {
        $mdlDesign = new Model_Design();
        $this->view->designs = $mdlDesign->listDesigns();

        $this->view->breadcrumbs = array(
           $this->view->getTranslation('Design') => $this->getFrontController()->getBaseUrl() . '/admin/design'
        );
        $this->design = new stdClass();
    }

    /**
     * Create action
     *
     * @return void
     */
    public function createAction()
    {
        if ($this->_request->isPost()) {
            // NOTE: we will turn this into a Zend_Form after were sure it will work this way
            $mdlDesign = new Model_Design();
            $name = Digitalus_Filter_Post::get('name');
            $notes = Digitalus_Filter_Post::get('notes');
            $id = $mdlDesign->createDesign($name, $notes);
            $this->_redirect('admin/design/update/id/' . $id);
            return;
        }
        $this->_forward('index');
    }

    /**
     * Update action
     *
     * @return void
     */
    public function updateAction()
    {
        $mdlDesign = new Model_Design();
        $this->view->designs = $mdlDesign->listDesigns();

        if ($this->_request->isPost()) {
            // NOTE: we will turn this into a Zend_Form after were sure it will work this way
            $id = Digitalus_Filter_Post::int('id');
            $mdlDesign->updateDesign(
                $id,
                Digitalus_Filter_Post::get('name'),
                Digitalus_Filter_Post::get('notes'),
                Digitalus_Filter_Post::get('layout'),
                Digitalus_Filter_Post::raw('skin'),
                Digitalus_Filter_Post::get('inline_styles'),
                Digitalus_Filter_Post::int('is_default')
            );
        } else {
            $id = $this->_request->getParam('id');
        }

        $mdlDesign->setDesign($id);
        $mdlPage = new Model_Page();
        $this->view->pages = $mdlPage->getPagesByDesign($id);

        $this->view->breadcrumbs[$this->view->getTranslation('Open') . ': ' . $this->view->getTranslation($mdlDesign->getValue('name'))] = $this->getFrontController()->getBaseUrl() . '/admin/design/update/id/' . $id;
        $this->view->toolbarLinks = array();
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->getFrontController()->getBaseUrl() . '/admin/index/bookmark'
            . '/url/admin_design_update_id_' . $id
            . '/label/' . $this->view->getTranslation('Design') . ':' . $mdlDesign->getValue('name');
        $this->view->toolbarLinks['Delete'] = $this->getFrontController()->getBaseUrl() . '/admin/design/delete/id/' . $id;


        $this->view->design = $mdlDesign;
    }

    /**
     * Delete action
     *
     * @return void
     */
    public function deleteAction()
    {
        $mdlDesign = new Model_Design();
        $id = $this->_request->getParam('id');
        $mdlDesign->deleteDesign($id);
        $this->_forward('index');
    }

}