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
 * RenderFileChecklist helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper  Digitalus_View_Helper_RenderFileChecklist
 */
class Digitalus_View_Helper_Filesystem_RenderFileChecklist extends Zend_View_Helper_Abstract
{
    public function renderFileChecklist($values = array(), $parentId, $level = 0, $class = 'fileChecklist', $icon = null)
    {
        $links = array();
        $page = new Model_Page();

        $children = $page->getChildren($parentId);

        foreach ($children as $child) {
            $submenu = false;
            if ($page->hasChildren($child)) {
                $newLevel = $level + 1;
                $submenu = $this->view->renderFileChecklist($values, $child->id, $newLevel, $class, $icon);
            }

            // TODO: refactor into Toolbox String - replace empty spaces with underscores for element names only
            $childName = strtolower(str_replace(' ', '_', $child->name));

            $checked = 0;
            if (in_array($childName, $values)) {
                $checked = 1;
            }

            $form = new Digitalus_Form();
            $checkbox = $form->createElement('checkbox', $childName, array(
                'value'         => $checked,
                'decorators'    => array('ViewHelper'),
                'belongsTo'     => $class,
            ));
            $links[] ='<li>' . $checkbox . $this->getIcon($icon, $child->name) . $child->name . $submenu . '</li>';
        }

        $strClass = null;
        if (is_array($links)) {
            if ($level == 0) {
                $strClass = 'class="' . $class . '"';
            }
            $fileChecklist = '<ul ' . $strClass . 'class="treeview">' . implode(null, $links) . '</ul>';
            return  $fileChecklist;
        }
    }

    public function addBaseUrl($path)
    {
        if (!empty($this->baseUrl)) {
            if (substr($path, 0, strlen($this->baseUrl) != $this->baseUrl)) {
                return $this->baseUrl . '/' . $path;
            }
        }
        return $path;
    }

    public function getIcon($icon, $alt)
    {
        $config = Zend_Registry::get('config');
        $this->iconPath = $config->filepath->icons;
        $iconPath = $this->iconPath;
        $iconPath = $this->addBaseUrl($iconPath . '/' . $icon);
        return '<img src="/' . $iconPath . '" title="' . htmlspecialchars($alt) . '" alt="' . htmlspecialchars($alt) . '" class="icon" />';
    }
}