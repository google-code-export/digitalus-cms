<?php
/**
 * LinkList helper
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
 * LinkList helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */
class Digitalus_View_Helper_Form_LinkList extends Zend_View_Helper_Abstract
{
    /**
     * comments
     */
    public function linkList($rowset, $linkTemplate, $replacements = array())
    {
        if (count($rowset) > 0) {
            foreach ($rowset as $row) {
                $link = $linkTemplate;
                foreach ($replacements as $tag) {
                    $link = str_replace('{' . $tag . '}', $row->$tag, $link);
                }
                $links[] = $link;
            }
            if (is_array($links)) {
                return $this->view->htmlList($links, null, null, false);
            }
        } else {
            return false;
        }
    }
}