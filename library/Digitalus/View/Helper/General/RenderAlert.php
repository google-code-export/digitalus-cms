<?php
/**
 * RenderAlert helper
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
 * @author      Forrest Lyman
 * @category    Digitalus
 * @package     Digitalus_View
 * @subpackage  Helper
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id:$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * RenderAlert helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper Digitalus_View_Helper_GetTranslation
 */
class Digitalus_View_Helper_General_RenderAlert extends Zend_View_Helper_Abstract
{
    /**
     * comments
     */
    public function renderAlert()
    {
        $m = new Digitalus_View_Message();
        $ve = new Digitalus_View_Error();
        $alert = false;
        $message = null;
        $verror = null;

        $alert = null;

        if ($ve->hasErrors()) {
            $verror = '<p>'. $this->view->getTranslation('The following errors have occurred') . ':</p>' . $this->view->htmlList($ve->get());
            $alert .= '<fieldset><legend>'. $this->view->getTranslation('Errors') . '</legend>' . $verror . '</fieldset>';
        }

        if ($m->hasMessage()) {
            $message .= '<p>' . $m->get() . '</p>';
            $alert   .= '<fieldset><legend>'. $this->view->getTranslation('Message') . '</legend>' . $message . '</fieldset>';
        }

        //after this renders it clears the errors and messages
        $m->clear();
        $ve->clear();

        return $alert;
    }
}