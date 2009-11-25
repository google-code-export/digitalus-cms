<?php
/**
 * GetXmlDeclaration helper
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
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id:$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.8.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * GetXmlDeclaration helper
 *
 * Checks the site settings for the use of an XML declaration
 * Returns a string containing the XML declaration
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.8.0
 * @uses        viewHelper Digitalus_View_Helper_Controls
 */
class Digitalus_View_Helper_Controls_GetXmlDeclaration extends Zend_View_Helper_Abstract
{
    protected $exclusion = array();

    /**
     * Return a string with the XML declaration if required
     *
     * Return a string with the XML declaration if required
     * If option 'browser' is used, it is decided by the browser's HTTP_USER_AGENT
     *
     * @param   string  $option  decide whether to set the XML declaration or not
     * @return  string|null  XML declaration or null
     */
    public function getXmlDeclaration($option = null)
    {
        // get xml declaration option from site settings if not given as argument
        if (is_null($option)) {
            $siteSettings = new Model_SiteSettings();
            $option = $siteSettings->get('xml_declaration');
        }
        // return null if content is not XHTML but simply HTML
        if (!$this->view->docType()->isXhtml()) {
            return null;
        }
        // decide whether to return an xml declaration depending on the option
        switch (strtolower($option)) {
            case 'never':
                return null;
            case 'always':
                return '<?xml version="1.0" encoding="' . $this->view->placeholder('charset') . '" ?>' . PHP_EOL;
            case 'browser':
                if ($this->_userAgentAcceptsXhtml()) {
                    return $this->getXmlDeclaration('always');
                } else {
                    return $this->getXmlDeclaration('never');
                }
            default:
                return null;
        }
    }

    /**
     * Checks whether HTTP User Agent excepts XHTML
     *
     * @return bool
     */
    protected function _userAgentAcceptsXhtml()
    {
        $check_pattern = '|application/xhtml\+xml(?!\s*;\s*q=0)|';
        // Does the UA claim to be able to handle XHTML?
        if (($_SERVER['SERVER_PROTOCOL'] == 'HTTP/1.1')
                && isset($_SERVER['HTTP_ACCEPT'])
                && preg_match($check_pattern, $_SERVER['HTTP_ACCEPT'])) {
            return true;
        }
        // Old Gecko Browsers do have some Crashbugs with XHTML.
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            if (preg_match("|rv\:0.9|", $_SERVER['HTTP_USER_AGENT'])) {
                return false;
            }
        }
        return false;
    }
}