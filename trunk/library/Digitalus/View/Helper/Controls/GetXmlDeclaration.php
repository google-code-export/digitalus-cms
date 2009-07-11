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
 * @uses        model Model_Browser
 */
class Digitalus_View_Helper_Controls_GetXmlDeclaration extends Zend_View_Helper_Abstract
{
    protected $exclusion = array();

    /**
     * Constructor - set the browsers, for which NO xml declaration shall be returned
     *
     * Possible browser names are the following:
     *     'Amaya', 'Android', 'Chrome', 'Firebird', 'Firefox', 'Galeon', 'GoogleBot',
     *     'iCab', 'Internet Explorer', 'iPhone', 'iPod', 'Konqueror', 'Lynx',
     *     'Mozilla', 'NetPositive', 'OmniWeb', 'Opera', 'Phoenix', 'Pocket Internet Explorer',
     *     'Safari', 'Yahoo! Slurp', 'WebTV', 'W3C Validator'
     * The version numer is seen as the latest version that IS excluded
     */
    public function __construct()
    {
        $this->exclusion = array(
            array('name' => 'Internet Explorer', 'version' => 6),
        );
    }

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
        if (!$this->view->isXhtml) {
            return null;
        }
        // decide whether to return an xml declaration depending on the option
        switch ($option) {
            case 'never':
                return null;
            case 'always':
                return '<?xml version="1.0" encoding="' . $this->view->placeholder('charset') . '" ?>' . PHP_EOL;
            case 'browser':
                $browser = new Model_Browser();
                foreach ($this->exclusion as $exclusion) {
                    if ($browser->getBrowser() == $exclusion['name'] && $browser->getVersion() <= $exclusion['version']) {
                        return $this->getXmlDeclaration('never');
                    }
                }
                return $this->getXmlDeclaration('always');
            default:
                return null;
        }
    }
}