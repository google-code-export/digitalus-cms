<?php
/**
 * Navigation Abstract Helper
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
 * @category    Digitalus
 * @package     Digitalus_View
 * @subpackage  Helper
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id:$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * Navigation Abstract Helper
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @uses        Digitalus_Acl
 * @uses        Digitalus_Auth
 * @since       Release 1.10.0
 */
class Digitalus_View_Helper_Navigation_Abstract extends Zend_View_Helper_Abstract
{
    protected $_attribs = array();

    protected function _setAttribs($attribs)
    {
        if (is_array($attribs)) {
            foreach ($attribs as $key => $value) {
                $this->_setAttrib($key, $value);
            }
        }
    }

    protected function _setAttrib($key, $value)
    {
        if (key_exists($key, $this->_attribs)) {
            $this->_attribs[$key] = (string)$value;
        }
    }

    protected function _getAttribs()
    {
        return $this->_attribs;
    }

    protected function _getAttrib($key)
    {
        if (key_exists($key, $this->_attribs)) {
            return $this->_attribs[$key];
        }
        return false;
    }
}