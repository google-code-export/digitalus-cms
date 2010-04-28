<?php
/**
 * RenderMenuBrowser helper
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
 * @version     $Id: RenderMenuBrowser.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * RenderMenuBrowser helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */
class Digitalus_View_Helper_Filesystem_RenderMenuBrowser extends Zend_View_Helper_Abstract
{
    public function renderMenuBrowser($parentId, $basePath = null, $id = 'menuTree')
    {
        $menu = new Model_Menu();

        $children = $menu->getMenuItems($parentId, true);

        foreach ($children as $child) {
            $label = $child->title;

            if (!empty($child->label)) {
                $label =  $child->label . ' / ' . $label;
            }

            $class = 'menu';
            $submenu = $this->view->renderMenuBrowser($child->id, $link);

            $linkId = Digitalus_Toolbox_String::addUnderscores($menu->path, true);
            $links[] = '<li class="menuItem"><a href="/admin/navigation/open/id/' . $child->id . '" class="' . $class . '" id="page-' . $child->id . '">' . $label . '</a>' . $submenu . '</li>';
        }

        if (is_array($links)) {
            if ($level == 0) {
                $strId = 'id="' . $id . '"';
            }
            return  '<ul ' . $strId . '>' . implode(null, $links) . '</ul>';
        }
    }
}