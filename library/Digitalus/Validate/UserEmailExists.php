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
class Digitalus_Validate_UserEmailExists extends Zend_Validate_Abstract
{
    const NOT_EXISTS = 'notEmailExists';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_EXISTS => "The given email '%value%' doesn't belong to the given username!",
    );

    /**
     * usernames for the given email address
     *
     * @var string
     */
    protected $_userName;

    /**
     * Sets validator options
     *
     * @param  array $options
     * @throws Digitalus_Validate_Exception
     * @return void
     */
    public function __construct($options = array())
    {
        if (!is_array($options)) {
            /**
             * @see Digitalus_Validate_Exception
             */
            require_once 'Digitalus/Validate/Exception.php';
            throw new Digitalus_Validate_Exception('The options of this validator have to be given as an array');
        } else {
            $options = array_unique($options);
            if (array_key_exists('username', $options)) {
                $this->setUserName($options['username']);
            }
        }
    }

    /**
     * Returns the user name option
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->_userName;
    }

    /**
     * Sets the user name option
     *
     * @param  array/string $name
     * @return void
     */
    public function setUserName($userName)
    {
        $this->_userName = $userName;
    }

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value is a valid email for the given user name
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        $value = (string)$value;
        $this->_setValue($value);

        $mdlUser = new Model_User();
        if (is_array($context) && isset($context['name']) && $mdlUser->userEmailExists($context['name'], $value)) {
            return true;
        } elseif (is_string($context) && $mdlUser->userEmailExists($context, $value)) {
            return true;
        }
        $this->_error(self::NOT_EXISTS);
        return false;
    }
}