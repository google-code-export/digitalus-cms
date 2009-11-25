<?php
/**
 * RenderBreadcrumbs helper
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
 * RenderBreadcrumbs helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */
class Digitalus_View_Helper_Navigation_RenderBreadcrumbs extends Zend_View_Helper_Abstract
{
    public function renderBreadcrumbs($separator = '', $siteRoot = 'Home')
    {
        if (!isset($separator) || empty($separator)) {
            $separator = ' > ';
        }
        $arrLabel = array();
        $arrPath  = array();
        $arrLinks = array();
        $page = Digitalus_Builder::getPage();
        $parents = $page->getParents();
        if (is_array($parents) && count($parents) > 0) {
            $path = '';
            foreach ($parents as $parent) {
                $arrLabel[] = Digitalus_Toolbox_Page::getLabel($parent);
            }
            $arrLabel = array_reverse($arrLabel);
            $startPath = '';
            $i = 0;
            foreach ($arrLabel as $label) {
                if ($i > 0) {
                    $startPath = $arrPath[$i - 1];
                }
                $arrPath[$i] = $startPath . '/' . Digitalus_Toolbox_String::addHyphens($label);
                $i++;
            }
            $i = 0;
            foreach ($arrLabel as $label) {
                $arrLinks[] = '<a href="' . $this->view->getBaseUrl() . $arrPath[$i] . '" class="breadcrumb">' . $arrLabel[$i] . '</a>';
                $i++;
            }
        }

        $pageLabel = Digitalus_Toolbox_Page::getLabel($page->getData());
        $arrLinks[] = '<span class="breadcrumb last">' . $pageLabel . '</span>';

        return implode($separator, $arrLinks);
    }
}