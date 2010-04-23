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
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
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
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @uses        Digitalus_Menu
 * @uses        View_Helper_Navigation
 * @uses        View_Helper_Navigation_Breadcrumbs
 * @since       Release 1.5.0
 */
class Digitalus_View_Helper_Navigation_RenderBreadcrumbs extends Digitalus_View_Helper_Navigation_Abstract
{
    protected $_attribs = array(
        'linkLast'          => false,
        'maxDepth'          => 1,
        'minDepth'          => 1,
        'separator'         => ' &gt; ',
    );

    public function renderBreadcrumbs($siteRoot = 'Home', $attribs = array())
    {
        $this->_setAttribs($attribs);

        // needed to register Navigation into Zend_Registry
        $menu = new Digitalus_Menu();

        return $this->view->navigation()->breadcrumbs()
            ->setLinkLast($this->_getAttrib('linkLast'))
            ->setMaxDepth($this->_getAttrib('maxDepth'))
            ->setMinDepth($this->_getAttrib('minDepth'))
            ->setSeparator($this->_getAttrib('separator'))
            ->render();
    }
}