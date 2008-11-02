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
 * @package   DSF_Core_Library
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: Action.php Tue Dec 25 20:13:29 EST 2007 20:13:29 forrest lyman $
 */

class DSF_Controller_Action extends Zend_Controller_Action 
{
	/**
	 * if the request is not found the run the core controller / method
	 *
	 * @return zend_controller_response
	 */
    public function __call($method, $args)
    {
        $module = $this->_request->getModuleName();
        $controller = $this->_request->getControllerName();
        $action = $this->_request->getActionName();

        if ('Action' == substr($method, -6) && $module != 'core') {
            // If the action method was not found, try the core controller
            return $this->_forward($action, $controller, 'core');
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "' . $method . '" called');
    }
    
    /**
     * this method allows you to run your code first, then add the functionality from
     * the core controller action
     *
     * @return zend_controller_response
     */
    public function addDefaultMethod()
    {
        $controller = $this->_request->getControllerName();
        $action = $this->_request->getActionName();
        return $this->_forward($action, $controller, 'core');
    }
    
}