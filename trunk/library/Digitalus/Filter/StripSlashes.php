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
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_Core_Library
 * @version     $Id:$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.9.0
 */

/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

/**
 * Magic Quotes Filter
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_Filter
 * @version     $Id:$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.9.0
 */
class Digitalus_Filter_StripSlashes implements Zend_Filter_Interface
{
    /**
     * Value to strip tags from
     *
     * @param string $value
     */
    public function filter($value)
    {
        if (get_magic_quotes_gpc()) {
            return stripslashes($value);
        } else {
            return $value;
        }
    }
}