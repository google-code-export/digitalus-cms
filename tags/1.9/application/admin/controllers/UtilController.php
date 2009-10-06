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
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
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
 * Admin Util Controller of Digitalus CMS
 *
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Controllers
 * @version     $Id:
 * @link        http://www.digitaluscms.com
 * @since       Release 1.0.0
 */
class Admin_UtilController extends Zend_Controller_Action
{
    /**
     * Render partial action
     *
     * @throws Digitalus_Exception
     * @return void
     */
    public function renderPartialAction()
    {
        $partial = $this->_request->getParam('partial');
        if ($partial != null) {
            $this->view->partialKey = Digitalus_Toolbox_String::stripUnderscores($partial);
            $data = new stdClass();
            $data->get = $this->_request->getParams();
            $data->post = $_POST;
            $this->view->data = $data;
        } else {
            throw new Digitalus_Exception($this->view->getTranslation('Invalid placeholder passed'));
        }
    }

}