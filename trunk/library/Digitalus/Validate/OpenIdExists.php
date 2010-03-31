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
 * @package     Digitalus_Validate
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: UpdateVersion110.php 677 2010-02-24 20:21:48Z lowtower@gmx.de $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 * @author      Lowtower - lowtower@gmx.de
 * @category    Digitalus CMS
 * @package     Digitalus_Validate
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */
class Digitalus_Validate_OpenIdExists extends Zend_Validate_Abstract
{
    const EXISTS = 'openIdExists';

    protected $_messageTemplates = array(
        self::EXISTS => "Another user already exists with the openId '%value%'!",
    );

    public function isValid($value)
    {
        $this->_setValue($value);

        $isValid = true;

        $mdlUser = new Model_User();

        if ($mdlUser->openIdExists($value)) {
            $this->_error(self::EXISTS);
            $isValid = false;
        }
        return $isValid;
    }
}