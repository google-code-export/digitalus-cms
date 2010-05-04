<?php
/**
 * Digitalus CMS
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
 * @author      Lowtower - lowtower@gmx.de
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_Form
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_Form_Element
 */
require_once 'Zend/Form/Element.php';

/**
 * Form Element Image
 *
 * @author      Lowtower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */
class Digitalus_Form_Element_Xml extends Zend_Form_Element
{
    public function getValue($toString = true)
    {
        $value = parent::getValue();
        if (is_array($value)) {
            $xml = new SimpleXMLElement('<elementData />');
            foreach ($value as $k => $v) {
                $xml->$k = $v;
            }
        } else if (!empty($value)) {
            $xml = simplexml_load_string($value);
        } else {
            return null;
        }
        if (is_object($xml)) {
            if ($toString) {
                return $xml->asXML();
            } else {
                return $xml;
            }
        }
        return null;
    }
    public function getXml()
    {
        return $this->getValue(false);
    }
}