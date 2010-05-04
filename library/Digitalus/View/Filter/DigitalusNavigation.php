<?php
/**
 * DigitalusNavigation
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
 * @since       Release 1.8.0
 */

/**
 * @see Digitalus_Content_Filter
 */
require_once 'Digitalus/Content/Filter.php';

/**
 * DigitalusNavigation
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.8.0
 * @uses        Digitalus_Content_Filter
 */
class Digitalus_View_Filter_DigitalusNavigation extends Digitalus_Content_Filter
{
    public $tag = 'digitalusNavigation';

    protected function _callback($matches)
    {
        $attribs = $this->getAttributes($matches[0]);
        if (is_array($attribs)) {
            $parentId  = isset($attribs['parent_id']) ? $attribs['parent_id'] : null;
            $root      = isset($attribs['root'])      ? $attribs['root']      : null;

            switch ($attribs['type']) {
                case 'menu':
                default:
                    return $this->view->menuRenderer($parentId, $attribs);
                    break;
                case 'submenu':
                    return $this->view->submenuRenderer($attribs);
                    break;
                case 'breadcrumbs':
                    return $this->view->breadcrumbsRenderer($root, $attribs);
                    break;
                case 'sitemap':
                    return $this->view->sitemapRenderer($attribs);
                    break;
            }
        }
        return null;
    }
}