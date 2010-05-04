<?php
/**
 * SitemapRenderer helper
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
 * @version     $Id$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * SitemapRenderer helper
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @uses        Digitalus_Menu
 * @since       Release 1.10.0
 */
class Digitalus_View_Helper_Navigation_SitemapRenderer extends Digitalus_View_Helper_Navigation_Abstract
{
    protected $_attribs = array(
        'indent'               => 4,
        'maxDepth'             => null,
        'minDepth'             => null,
        'formatOutput'         => false,
        'useSchemaValidation'  => false,
        'useSitemapValidators' => true,
        'useXmlDeclaration'    => true,
    );
    protected $_booleanValues = array('formatOutput', 'useSchemaValidation', 'useSitemapValidators', 'useXmlDeclaration');

    public function sitemapRenderer($parentId = 0, $attribs = array())
    {
        // needed to register Navigation into Zend_Registry
        $menu = new Digitalus_Menu($parentId);

        $this->_setAttribs($attribs);

        // render sitemap
        return $this->view->navigation()->sitemap()
            ->setIndent($this->_getAttrib('indent'))
            ->setMaxDepth($this->_getAttrib('maxDepth'))
            ->setMinDepth($this->_getAttrib('minDepth'))
            ->setFormatOutput($this->_getAttrib('formatOutput'))
            ->setUseSchemaValidation($this->_getAttrib('useSchemaValidation'))
            ->setUseSitemapValidators($this->_getAttrib('useSitemapValidators'))
            ->setUseXmlDeclaration($this->_getAttrib('useXmlDeclaration'))
            ->render();
    }
}