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
 * @version    $Id: ModuleController.php Mon Dec 24 20:57:41 EST 2007 20:57:41 forrest lyman $
 */

class Core_ModuleController extends Zend_Controller_Action 
{
	public function init()
	{
		//set the admin
		$this->view->adminSection = 'module';
	}
	
	/**
	 * this displays the main module admin page
	 * note that each of the actual modules manages themselved.  this serves as a dashboard for them
	 * to ease integration with the admin interface
	 *
	 */
	public function indexAction()
	{}
	
	/**
	 * renders the select control for each of the actions available on the selected module
	 * used for the add module interface
	 *
	 */
	public function selectModulePageAction()
	{
	    $this->view->moduleName = $this->_request->getParam('moduleName');
	    $this->view->val = $this->_request->getParam('val');
	}
	
	/**
	 * if the selected module / action has a form this will render the form
	 *
	 */
	public function renderFormAction()
	{
	    $this->view->moduleName = $this->_request->getParam('modulename');
	    $this->view->moduleAction= $this->_request->getParam('moduleaction');
	}
}