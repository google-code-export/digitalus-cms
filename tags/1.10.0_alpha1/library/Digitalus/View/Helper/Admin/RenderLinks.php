<?php
/**
 * RenderLinks helper
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
 * RenderLinks helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper Digitalus_View_Helper_GetTranslation
 */
class Digitalus_View_Helper_Admin_RenderLinks extends Zend_View_Helper_Abstract
{
    /**
     * comments
     */
    public function renderLinks($links, $class, $prependText = null, $appendText = null, $separator = ' | ')
    {
        if (is_array($links) && count($links) > 0) {
            foreach ($links as $label => $link) {
                $linkClass = strtolower($label);
                $linkClass = str_replace(' ', '_', $linkClass);
                $hyperlinks[] = '<a href="' . $link . '" class="' . $linkClass . '">' . $this->view->getTranslation($label) . '</a>';
            }
            return '<p class="' . $class . '">' . $prependText . implode($separator, $hyperlinks) . $appendText . '</p>';
        }
    }
}