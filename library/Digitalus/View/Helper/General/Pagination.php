<?php
/**
 * Pagination helper
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
 * Pagination helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper Digitalus_View_Helper_GetTranslation
 */
class Digitalus_View_Helper_General_Pagination extends Zend_View_Helper_Abstract
{
    /**
     * creates links to paginate lists
     */
    public function pagination($baseUrl, $currentPage, $pages)
    {
        if ($pages > 1) {
           //setup the direct links to each page
           $directLinks = '';
            for ($i=1; $i <= $pages; $i++) {
                if ($i == $currentPage) {
                    $class = 'selected';
                } else {
                    $class = '';
                }
                $directLinks .= '<a href="' . $baseUrl . '/page/' . $i . '" class="' . $class . '">' . $i . '</a>';
            }
            //first page
            $xhtml = '<a href="' . $baseUrl . '/page/1">&lt;&lt; ' . $this->view->getTranslation('First') . '</a>';

            //previous page
            if ($currentPage > 1) {
                $previous = $currentPage - 1;
                $xhtml .= '<a href="' . $baseUrl . '/page/' . $previous . '">&lt; ' . $this->view->getTranslation('Previous') . '</a>';
            }

            //direct links
            $xhtml .= $directLinks;

            //next page
            if ($currentPage < $pages) {
                $next = $currentPage + 1;
                $xhtml .= '<a href="' . $baseUrl . '/page/' . $next . '">' . $this->view->getTranslation('Next') . ' &gt;</a>';
            }
            //last page
            $xhtml .= '<a href="' . $baseUrl . '/page/' . $pages . '">' . $this->view->getTranslation('Last') .  '&gt;&gt;</a>';

            return $xhtml;
        }
    }
}