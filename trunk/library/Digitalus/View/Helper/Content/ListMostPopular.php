<?php
/**
 * ListMostPopular helper
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
 * @version     $Id: ListMostPopular.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * ListMostPopular helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper Digitalus_View_Helper_RealPath
 */
class Digitalus_View_Helper_Content_ListMostPopular extends Zend_View_Helper_Abstract
{
    /**
     * render a module page like news_showNewPosts
     */
    public function listMostPopular()
    {
        $popular = $this->view->pageObj->getPopularStories();
        if ($popular) {
            foreach ($popular as $story) {
                $link = Digitalus_Toolbox_String::addHyphens($this->view->realPath($story->id));
                $data[] = '<a href="' . $link . '">' . $this->view->pageObj->getLabel($story) . '</a>';
            }
            if (is_array($data)) {
                return $this->view->htmlList($data);
            }
        }
    }
}