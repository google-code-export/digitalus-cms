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
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_View
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: RenderMenu.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
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
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        Digitalus_Menu
 * @uses        View_Helper_Navigation
 * @uses        View_Helper_Navigation_Menu
 */
class Digitalus_View_Helper_Navigation_RenderMenu extends Digitalus_View_Helper_Navigation_Abstract
{
    protected $_attribs = array(
        'indent'            => 4,
        'maxDepth'          => 0,
        'minDepth'          => 0,
        'onlyActiveBranch'  => false,
        'renderParents'     => true,
        'ulClass'           => 'vlist',
    );

    public function renderMenu($parentId = 0, $attribs = array())
    {
        // needed to register Navigation into Zend_Registry
        $menu = new Digitalus_Menu($parentId);

        $this->_setAttribs($attribs);

        return $this->view->navigation()->menu()->renderMenu(null, $this->_getAttribs());
    }
}