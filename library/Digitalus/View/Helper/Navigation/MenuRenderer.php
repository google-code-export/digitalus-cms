<?php
/**
 * MenuRenderer helper
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
 * MenuRenderer helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @uses        Digitalus_Menu
 * @since       Release 1.5.0
 */
class Digitalus_View_Helper_Navigation_MenuRenderer extends Digitalus_View_Helper_Navigation_Abstract
{
    protected $_attribs = array(
        'indent'            => 4,
        'maxDepth'          => null,
        'minDepth'          => null,
        'onlyActiveBranch'  => false,
        'renderParents'     => true,
        'ulClass'           => 'vlist',
    );
    protected $_booleanValues = array('onlyActiveBranch', 'renderParents');

    public function menuRenderer($parentId = 0, $attribs = array())
    {
        if (!Zend_Registry::isRegistered('Zend_Navigation')) {
            // needed to register Navigation into Zend_Registry
            $menu = new Digitalus_Menu($parentId);
        }
        $this->_setAttribs($attribs);

        // render partial
        if ($this->_issetPartial($attribs)) {
            return $this->view->navigation()->menu()->renderPartial(null, $attribs['partial']);
        }
        // render menu
        return $this->view->navigation()->menu()->renderMenu(null, $this->_getAttribs());
    }
}