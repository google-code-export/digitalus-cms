<?php
/**
 * DigitalusTag
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
 * @version     $Id: DigitalusTag.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.8.0
 */

/**
 * @see Digitalus_Content_Filter
 */
require_once 'Digitalus/Content/Filter.php';

/**
 * DigitalusTag
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.8.0
 */
class Digitalus_View_Helper_DigitalusTag extends Zend_View_Helper_Abstract
{
    public function digitalusTag($tag, $id, $attr = array())
    {
        $xhtml = '<digitalus' . ucfirst(strip_tags($tag));
        $attr['id'] = $id;
        foreach ($attr as $key => $value) {
            $xhtml .= ' ' . strip_tags($key) . "='" . strip_tags($value) . "'";
        }
        $xhtml .= ' />';
        return  $xhtml;
    }
}