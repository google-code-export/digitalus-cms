<?php
/**
 * RenderMenu helper
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
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
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
 * RenderMenu helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper  Digitalus_View_Helper_RenderMenu
 */
class Digitalus_View_Helper_Navigation_RenderMenu extends Zend_View_Helper_Abstract
{
    public $levels = 1;

    public function renderMenu($parentId = 0, $levels = 1, $currentLevel = 1, $id = 'menu')
    {
        if (null == $currentLevel) {
            $currentLevel = 1;
        }

        $menu = new Digitalus_Menu($parentId);
        $links = array();

        if (count($menu->items) > 0) {
            foreach ($menu->items as $item) {
                $data = new stdClass();
                $data->item = $item;
                $data->menuId = $id;

                //check for a submenu
                if (($levels > $currentLevel) && ($item->hasSubmenu)) {
                    $newLevel = $currentLevel + 1;
                    $data->submenu = $this->view->renderMenu($item->id, $levels, $newLevel, 'submenu_' . $item->id);
                } else {
                    $data->submenu = null;
                }

                $menuItem = "<li id='{$id}_item_wrapper_{$item->id}' class='menuItem'>";
                $class = $item->isSelected() ? 'selected' : 'unselected';

                if ($item->isSelected()){
                    $class = 'selected';
                } else {
                    $class = 'unselected';
                }

                $menuItemId = $id . '_item_' . $item->id;
                $menuItem .= $item->asHyperlink($menuItemId, $class);
                if ($data->submenu != null) {
                    $menuItem .= $data->submenu;
                }
                $menuItem .= '</li>';

                $links[] = $menuItem;
                unset($menuItem);
            }
        }

        if (count($links) > 0) {
            return  "<ul id='{$id}'>" . implode(null, $links) . '</ul>';
        } else {
            return null;
        }
    }
}