<?php
/**
 * RadioXmlDeclaration helper
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
 * RadioXmlDeclaration helper
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.8.0
 * @uses        viewHelper Digitalus_View_Helper_Controls
 */
class Digitalus_View_Helper_Controls_RadioXmlDeclaration extends Zend_View_Helper_Abstract
{
    /**
     * @param   string  $name
     * @param   string  $value
     * @param   array   $attribs
     * @return  string  HTML radio input element
     */
    public function radioXmlDeclaration($name, $value, $attribs = null, $options = null, $listsep = "\n")
    {
        $options = array(
            'always'  => $this->view->getTranslation('Always'),
            'never'   => $this->view->getTranslation('Never'),
            'browser' => $this->view->getTranslation('By browser check'),
        );
        return $this->view->formRadio($name, $value, $attribs, $options, $listsep);
    }
}