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
 * Admin Module Controller of Digitalus CMS
 *
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Controllers
 * @version     $Id: ModuleController.php Mon Dec 24 20:57:41 EST 2007 20:57:41 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.0.0
 */
class Admin_ModuleController extends Digitalus_Controller_Action
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
           $this->view->getTranslation('Modules') => $this->baseUrl . '/admin/module'
        );
    }

    /**
     * The default action
     *
     * This displays the main module admin page
     * note that each of the actual modules manages themselves. This serves as a dashboard for them
     * to ease integration with the admin interface
     *
     * @return void
     */
    public function indexAction()
    {
    }

    /**
     * Select module page action
     *
     * Renders the select control for each of the actions available on the selected module
     * used for the add module interface
     *
     * @return void
     */
    public function selectModulePageAction()
    {
        $this->view->moduleName = $this->_request->getParam('moduleName');
        $this->view->val = $this->_request->getParam('val');
    }

    /**
     * Render form action
     *
     * If the selected module / action has a form this will render the form
     *
     * @return void
     */
    public function renderFormAction()
    {
        $module  = $this->_request->getParam('moduleKey');
        $element = $this->_request->getParam('element');
        if ($module != null) {
            $moduleParts = explode('_', $module);
            if (is_array($moduleParts) && count($moduleParts) == 2) {
                $action = $moduleParts[1];
                $name   = $moduleParts[0];

                $data = new stdClass();
                $data->get  = $this->_request->getParams();
                $data->post = $_POST;
                $this->view->data    = $data;
                $this->view->element = $element;

                $dir      = APPLICATION_PATH . '/modules/' . $name . '/views/scripts';
                $helpers  = APPLICATION_PATH . '/modules/' . $name . '/views/helpers';
                $path     = '/public/' . $action . '.form.phtml';
                $fullPath = $dir . $path;

                if (file_exists($fullPath)) {
                    $this->view->addScriptPath($dir);
                    $this->view->addHelperPath($helpers, 'Digitalus_View_Helper');
                    $this->view->placeholder('moduleForm')->set($this->view->render($path));
                }
            }
        }
    }
}