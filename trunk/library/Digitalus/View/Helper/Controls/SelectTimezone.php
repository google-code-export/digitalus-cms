<?php
/**
 * SelectTimezone helper
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
 * @version     $Id: SelectTimezone.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.8.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * SelectTimezone helper
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.8.0
 * @uses        viewHelper Digitalus_View_Helper_Controls
 */
class Digitalus_View_Helper_Controls_SelectTimezone extends Zend_View_Helper_Abstract
{
    /**
     * @param   string  $name   name of the select tag
     * @param   string  $value  value for the select tag
     * @param   array   $attr   attributes for the select tag
     * @return  string  HTML select element
     */
    public function selectTimezone($name, $value, $attribs = null)
    {
        $options = Digitalus_Validate_Timezone::getValidTimezones(null, true);

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