<?php
/**
 * RenderBookmarks helper
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
 * @author      LowTower - lowtower@gmx.de
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_View
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * RenderBookmarks helper
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @uses        Model_Bookmark
 * @uses        Digitalus_Toolbox_String
 * @uses        Digitalus_View_Helper_Interface_Link
 * @since       Release 1.10.0
 */
class Digitalus_View_Helper_Admin_RenderBookmarks extends Zend_View_Helper_Abstract
{
    public function renderBookmarks($bookmarks = null)
    {
        $xhtml = '';
        if (!is_array($bookmarks)) {
            $mdlBookmark = new Model_Bookmark();
            $bookmarks = $mdlBookmark->getUsersBookmarks();
        }
        if (!empty($bookmarks)) {
            $xhtml = '<ul class="bookmarks">';
            foreach ($bookmarks as $bookmark) {
                $xhtml .= '    <li>' . PHP_EOL;
                $xhtml .= '        ' . $this->view->link(null, '/admin/index/delete-bookmark/id/' . $bookmark->id, 'link_delete.png', 'clear') . PHP_EOL;
                $url = $bookmark->url;
                if (strpos($url, 'mod_') === 0) {
                    $url = str_replace('mod_', '', $url);
                    $url = Digitalus_Toolbox_String::stripUnderscores($url);
                    $url = 'mod_' . $url;
                } else {
                    $url = Digitalus_Toolbox_String::stripUnderscores($url);
                }
                $xhtml .= '        ' . $this->view->link($bookmark->label, '/' . $url);
                $xhtml .= '    </li>';
            }
            $xhtml .= '</ul>';
        } else {
            $xhtml = $this->view->getTranslation('You do not have any bookmarks.');
        }
        return $xhtml;
    }
}