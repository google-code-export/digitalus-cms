<?php
/**
 * HighlightText helper
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
 * HighlightText helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */
class Digitalus_View_Helper_General_HighlightText extends Zend_View_Helper_Abstract
{
    /**
     * if string is separated into multiple words this will hightlight each indipendently
     */
    public function highlightText($content, $string)
    {
        //match upper and lower case
        $upper = explode(' ', ucwords($string));
        $lower = explode(' ', strtolower($string));

        $string = array_merge($upper, $lower);

        foreach ($string as $str) {
            $content = str_replace($str, '[bOp]' . $str . '[bCl]', $content);
        }

        $content = str_replace('[bOp]', '<strong class="highlight">', $content);
        $content = str_replace('[bCl]', '</strong>', $content);
        return $content;
    }
}