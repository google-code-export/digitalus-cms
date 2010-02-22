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
 * @see Zend_Controller_Action
 */
require_once 'Zend/Controller/Action.php';

/**
 * Admin Support Controller of Digitalus CMS
 *
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Controllers
 * @version     $Id: SupportController.php Tue Dec 25 19:48:11 EST 2007 19:48:11 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.0.0
 */
class Admin_SupportController extends Zend_Controller_Action
{
    /**
     * Initialize the action
     *
     * @return void
     */
    public function init()
    {
        $this->view->adminSection = 'support';
    }

    /**
     * The default action
     *
     * @return void
     */
    public function indexAction()
    {

    }
}