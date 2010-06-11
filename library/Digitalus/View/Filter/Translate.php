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
 * @author      LowTower - lowtower@gmx.de
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_View
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: Translate.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10.0
 */

/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

/**
 * Translate Filter
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10.0
 */
class Digitalus_View_Filter_Translate implements Zend_Filter_Interface
{
    /**
    * Starting delimiter for translation snippets in view
    *
    */
    const I18N_DELIMITER_START = '<i18n>';

    /**
    * Ending delimiter for translation snippets in view
    *
    */
    const I18N_DELIMITER_END = '</i18n>';

    /**
    * Filter the value for i18n Tags and translate
    *
    * @param string $value
    * @return string
    */
    public function filter($value)
    {
        $startDelimiterLength = strlen(self::I18N_DELIMITER_START);
        $endDelimiterLength   = strlen(self::I18N_DELIMITER_END);

        $translator = Zend_Registry::get('Zend_Translate');

        $offset = 0;
        while (($posStart = strpos($value, self::I18N_DELIMITER_START, $offset)) !== false) {
            $offset = $posStart + $startDelimiterLength;
            if (($posEnd = strpos($value, self::I18N_DELIMITER_END, $offset)) === false) {
                throw new Digitalus_View_Exception('No ending tag after position [' . $offset . '] found!');
            }
            $translate = substr($value, $offset, $posEnd - $offset);
            $translate = $translator->_($translate);
            $offset    = $posEnd + $endDelimiterLength;
            $value     = substr_replace($value, $translate, $posStart, $offset - $posStart);
            $offset    = $offset - $startDelimiterLength - $endDelimiterLength;
        }
        return $value;
    }
}