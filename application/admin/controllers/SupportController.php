<?php
 

/**
 * Digitalus Site Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://digitalus-media.com/dsf/license
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@digitalus-media.com so we can send you a copy immediately.
 *
 * @category   DSF
 * @package    DSF admin controllers
 * @copyright  Copyright (c) 2007 Digitalus Media USA - [digitalus-media.com]
 * @license    http://digitalus-media.com/dsf/license     New BSD License
 * @version    $Id: admin_support_controller 12-10-17 flyman $
 */
class Admin_SupportController extends DSF_Controller_Action 
{
	public function init()
	{
		$this->view->adminSection = 'support';
	}

}