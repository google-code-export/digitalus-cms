<?php
/**
 * RenderFileChecklist helper
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
 * RenderFileChecklist helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper  Digitalus_View_Helper_RenderFileChecklist
 */
class Digitalus_View_Helper_Filesystem_RenderFileChecklist extends Zend_View_Helper_Abstract
{
    public function renderFileChecklist($values = array(), $parentId, $level = 0, $id = 'fileChecklist')
    {
        $links = array();
        $page = new Model_Page();

        $children = $page->getChildren($parentId);

        foreach ($children as $child) {
            if ($page->hasChildren($child)) {
                $newLevel = $level + 1;
                $submenu = $this->view->renderFileChecklist($values, $child->id, $newLevel);
            } else {
                $submenu = false;
            }

            if (in_array($child->id, $values)) {
                $checked = 1;
            } else {
                $checked = 0;
            }

            $checkbox = $this->view->formCheckbox('file_' . $child->id, $checked);

            $links[] ='<li class="page">' . $checkbox . $child->name . $submenu . '</li>';
        }

        if (is_array($links)) {
            if ($level == 0) {
                $strId = "id='{$id}'";
            } else {
                $strId = null;
            }
            $fileChecklist = "<ul {$strId}>" . implode(null, $links) . '</ul>';
            return  $fileChecklist;
        }
    }
}