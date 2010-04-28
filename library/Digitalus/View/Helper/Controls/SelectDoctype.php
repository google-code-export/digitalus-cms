<?php
/**
 * SelectDoctype helper
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
 * @version     $Id: SelectDoctype.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * SelectDoctype helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper Digitalus_View_Helper_Controls
 * @uses        Digitalus_Form
 */
class Digitalus_View_Helper_Controls_SelectDoctype extends Zend_View_Helper_Abstract
{
    /**
     *
     */
    public function selectDoctype($name, $value, $attribs = null)
    {
        $options = array(
            'XHTML1_TRANSITIONAL' => 'XHTML1_TRANSITIONAL',
            'XHTML11'             => 'XHTML11',
            'XHTML1_STRICT'       => 'XHTML1_STRICT',
            'XHTML1_FRAMESET'     => 'XHTML1_FRAMESET',
            'XHTML_BASIC1'        => 'XHTML_BASIC1',
            'HTML4_STRICT'        => 'HTML4_STRICT',
            'HTML4_LOOSE'         => 'HTML4_LOOSE',
            'HTML4_FRAMESET'      => 'HTML4_FRAMESET'
        );
        $form = new Digitalus_Form();
        $select = $form->createElement('select', $name, array(
            'multiOptions'  => $options,
            'value'         => $value,
        ));
        if (is_array($attribs)) {
            $select->setAttribs($attribs);
        }
        return $select;
    }
}