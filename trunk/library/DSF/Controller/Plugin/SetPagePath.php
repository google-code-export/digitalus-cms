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
 * @version    $Id: SetPagePath.php Tue Dec 25 20:30:05 EST 2007 20:30:05 forrest lyman $
 */

class DSF_Controller_Plugin_SetPagePath extends Zend_Controller_Plugin_Abstract
{
    /**
     * this function routes all requests that come in to the default module to the index controller / index action
     *
     * @param zend_controller_request $request
     */
    public function preDispatch($request)
    {
		if($request->module == 'public' && $request->controller != 'plugin')
		{
            $request->setControllerName('index');
            $request->setActionName('index');
		}
    	
    }
}