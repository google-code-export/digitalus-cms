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
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_View
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: Abstract.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
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
abstract class Digitalus_View_Helper_Navigation_Abstract extends Zend_View_Helper_Abstract
{
    /**
     * Attributes for the specific navigation helper
     * @var array
     */
    protected $_attribs = array();

    /**
     * Attributes that must be booleanised before processing them
     * @var array
     */
    protected $_booleanValues = array();

    /**
     * Sets multiple attributes at a time
     *
     * @param  array $attribs
     * @return void
     */
    protected function _setAttribs($attribs)
    {
        if (is_array($attribs)) {
            foreach ($attribs as $key => $value) {
                $this->_setAttrib($key, $value);
            }
        }
    }

    /**
     * Sets one attribute
     *
     * @param  string $key
     * @param  string $value
     * @return void
     */
    protected function _setAttrib($key, $value)
    {
        if (key_exists($key, $this->_attribs)) {
            if (in_array($key, $this->_booleanValues)) {
                // needed to convert "true" or "false" strings into boolean values
                $this->_attribs[$key] = Digitalus_Toolbox_String::booleanise($key);
            } else {
                $this->_attribs[$key] = (string)$value;
            }
        }
    }

    /**
     * Returns all attributes
     *
     * @return array
     */
    protected function _getAttribs()
    {
        return $this->_attribs;
    }

    /**
     * Returns the value for one specific attribute or false if it doesn't exist
     *
     * @param  string $key
     * @return string|false
     */
    protected function _getAttrib($key)
    {
        if (key_exists($key, $this->_attribs)) {
            return $this->_attribs[$key];
        }
        return null;
    }

    /**
     * Returns true if a partial was set, otherwise false
     *
     * @param  array   $attribs
     * @return boolean
     */
    protected function _issetPartial(array $attribs)
    {
        if (isset($attribs['partial']) && !empty($attribs['partial']) && '' != $attribs['partial']) {
            return true;
        }
        return false;
    }
}