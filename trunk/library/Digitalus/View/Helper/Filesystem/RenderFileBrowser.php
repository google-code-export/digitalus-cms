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
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_View
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: RenderFileBrowser.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
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
 */
class Digitalus_View_Helper_Filesystem_RenderFileBrowser extends Zend_View_Helper_Abstract
{
    public function renderFileBrowser($parentId, $link, $basePath = null, $level = 0,
                                      $id = 'fileTree', $withRoot = false, $current = null,
                                      $exclude = null, $translate = true)
    {
        $links = array();
        $tree = new Model_Page();

        $children = $tree->getChildren($parentId);

        // add a link for site root
        if (isset($withRoot) && $withRoot == true) {
            $links[] = $this->_getSiteRootElement();
        }

        foreach ($children as $child) {
            if ($tree->hasChildren($child)) {
                $newLevel = $level + 1;
                $submenu = $this->view->renderFileBrowser($child->id, $link, $basePath, $newLevel, null, null, $current, $exclude);
                $icon = 'folder.png';
                if ($child->id == $current) {
                    $icon = 'folder_wrench.png';
                }
            } else {
                $icon = 'page_white_text.png';
                if ($child->id == $current) {
                    $icon = 'page_white_wrench.png';
                }
                $submenu = false;
            }
            if (isset($child->label) && !empty($child->label)) {
                $label = $child->label;
            } else {
                $label = $child->name;
            }
            if ($child->id == $exclude) {
                $links[] = $this->_getExcludeElement($label, $submenu, $icon);
            } else {
                $links[] = '<li class="menuItem">' . $this->view->link($label, $link . $child->id, $icon, null, null, null, $translate) . $submenu . '</li>';
            }
        }

        if (is_array($links)) {
            if ($level == 0) {
                $strId = 'id="' . $id . '"';
            } else {
                $strId = null;
            }
            $filetree = "<ul {$strId}>" . implode(null, $links) . '</ul>';
            return  $filetree;
        }
    }

    /**
     * Get Site Root element
     *
     * @return  string
     */
    protected function _getSiteRootElement()
    {
        $request = $this->view->getRequest();
        $pageId  = $request->getParam('id', 0);

        $siteRoot = '<li class="menuItem" style="background-image: none; padding: 0px;">'
                  . '  <a class="link" href="' . $this->view->getBaseUrl() . '/admin/page/move/id/' . $pageId . '/parent/0">'
                  . '    <img class="icon" alt="' . $this->view->getTranslation('Site Root') . '" src="' . $this->view->getBaseUrl() . '/images/icons/silk/folder.png"/>'
                  . '    ' . $this->view->getTranslation('Site Root')
                  . '  </a>'
                  . '</li>';

        return $siteRoot;
    }

    /**
     * Get exclude substitute element
     *
     * @return  string
     */
    protected function _getExcludeElement($label, $submenu, $icon)
    {
        return '<li class="menuItem">'
             . '    <img class="icon" alt="' . $label . '" src="' . $this->view->getBaseUrl() . '/images/icons/silk/' . $icon . '"/>'
             . $label . $submenu
             . '</li>';
    }
}