<?php
/**
 * RenderFileBrowser helper
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
 * RenderFileBrowser helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper  Digitalus_View_Helper_RenderFileBrowser
 */
class Digitalus_View_Helper_Filesystem_RenderFileBrowser extends Zend_View_Helper_Abstract
{
    public function renderFileBrowser($parentId, $depth = 1 , $level = 0, $basePath = null, $id = 'fileTree')
    {
        // @todo: deal with selected menu items
        if ($level <= $depth - 1) {
            $links = array();
            $menu = new Model_Menu();

            $children = $menu->getMenuItems($parentId);

            foreach ($children as $child) {
                if (!empty($child->label)) {
                    $label = $child->label;
                } else {
                    $label = $child->title;
                }

                $children = $menu->getMenuItems($child->id);
                if ($children->count() > 0) {
                    $class = 'dir';
                    $newLevel = $level + 1;
                    $submenu = $this->view->renderFileBrowser($child->id, $depth, $newLevel, $link);
                } else {
                    $class = 'page';
                    $submenu = false;
                }
                $linkId = Digitalus_Toolbox_String::addUnderscores($menu->path, true);
                $links[] = '<li class="menuItem"><a href="/admin/page/open/id/' . $child->id . '" class="' . $class . '" id="page-' . $child->id . '">' . $label . '</a>' . $submenu . '</li>';
            }
        }

        if (is_array($links)) {
            if ($level == 0) {
                $strId = 'id="' . $id . '"';
            }
            return  '<ul ' . $strId . '>' . implode(null, $links) . '</ul>';
        }
    }
}