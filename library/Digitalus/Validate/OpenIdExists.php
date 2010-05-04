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
 * @subpackage  Digitalus_Validate
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 * @author      Lowtower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */
class Digitalus_Validate_OpenIdExists extends Zend_Validate_Abstract
{
    const EXISTS = 'openIdExists';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::EXISTS => "Another user already exists with the openId '%value%'!",
    );

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value does exist already as openid
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $value = (string)$value;
        $this->_setValue($value);

        $mdlUser = new Model_User();
        if (!$mdlUser->openIdExists($value)) {
            return true;
        }
        $this->_error(self::EXISTS);
        return false;
    }
}