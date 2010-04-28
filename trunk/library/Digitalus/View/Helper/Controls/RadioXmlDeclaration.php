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
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_View
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: RadioXmlDeclaration.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
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
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.8.0
 * @uses        viewHelper Digitalus_View_Helper_Controls
 * @uses        Digitalus_Form
 */
class Digitalus_View_Helper_Controls_RadioXmlDeclaration extends Zend_View_Helper_Abstract
{
    /**
     * @param   string  $name
     * @param   string  $value
     * @param   array   $attribs
     * @return  string  HTML radio input element
     */
    public function radioXmlDeclaration($name, $value, $attribs = null, $options = null, $listSep = "\n")
    {
        $options = array(
            'always'  => $this->view->getTranslation('Always'),
            'never'   => $this->view->getTranslation('Never'),
            'browser' => $this->view->getTranslation('By browser check'),
        );
        $form = new Digitalus_Form();
        $radio = $form->createElement('radio', $name, array(
            'multiOptions'  => $options,
            'value'         => $value,
            'listSep'       => $listSep,
        ));
        if (is_array($attribs)) {
            $radio->setAttribs($attribs);
        }
        return $radio;
    }
}