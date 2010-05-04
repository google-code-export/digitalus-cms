<?php
/**
 * FormatCurrency helper
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
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_View
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * CleanUri helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */
class Digitalus_View_Helper_General_CleanUri extends Zend_View_Helper_Abstract
{
    /**
     * removes any params from the uri
     */
    public function CleanUri($uri = null, $absolute = false, $stripUnderscores = false)
    {
        if ($uri == null) {
           $uri = $this->view->pageObj->getCleanUri();
        }
        if ($absolute && !empty($uri)) {
            $uri = '/' . $uri;
        }
        if ($stripUnderscores) {
            $uri = Digitalus_Toolbox_String::stripUnderscores($uri, true);
        }
        return  Digitalus_Toolbox_String::addHyphens($uri);
    }
}