<?php
/**
 * RenderPublishedPagesList helper
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
 * @version     $Id: RenderPublishedPagesList.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.9.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * RenderPublishedPagesList helper
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.9.0
 */
class Digitalus_View_Helper_Admin_RenderPublishedPagesList extends Zend_View_Helper_Abstract
{
    public function renderPublishedPagesList($publishLevel = null, $id = 'pagesList', $order = null, $limit = null, $offset = null)
    {
        $mdlPage = new Model_Page();
        $pages = $mdlPage->getPagesByPublishState($publishLevel, $order, $limit, $offset);

        if (is_array($pages)) {
            $xhtml = '<ul id="' . $id . '">';

            foreach ($pages as $pageId) {
                $page = new Model_Page();
                $title = $page->getPageTitle($pageId);

                $xhtml .= '<li class="page">' . $this->view->link($title, '/admin/page/edit/id/' . $pageId, 'page.png') . '</li>'. PHP_EOL;
            }
            $xhtml .= '</ul>' . PHP_EOL;

            return $xhtml;
        }
        return null;
    }
}