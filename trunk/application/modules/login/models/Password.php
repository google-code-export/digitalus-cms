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
 * @package     Digitalus_CMS_Module_Login
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: Password.php Mon Dec 24 20:38:38 EST 2007 20:38:38 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */

/**
 * Password model
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */
class Login_Password
{
    /**
     * The Password itself.
     *
     * @var string
     */
    protected $_randomPassword = '';

    /**
     * Array with options for the password.
     *
     * @var array
     */
    protected $_attribs = array(
        'passwordLength'  => 8,                     // set password length
        'lowerAsciiBound' => 50,                    // set ASCII range for random character generation
        'upperAsciiBound' => 122,
        'regex'           => "/\d{1,}[A-Za-z]{1,}/",      // set additional regex to check password against
    );

    /**
     * Array special characters and some confusing alphanumerics that should be excluded
     * o,O,0,I,1,l etc.
     *
     * @var array
     */
    protected $_notUse = array(58, 59, 60, 61, 62, 63, 64, 73, 79, 91, 92,
                               93, 94, 95, 96, 108, 111);

    /**
     * Constructor
     *
     * @param  array $attribs Array with password options
     * @return void
     */
    public function __construct($attribs = array())
    {
        $this->_setAttribs($attribs);
    }

    /**
     * Creates a random password
     *
     * @return void
     */
    protected function _createRandomPassword()
    {
        $i = 0;
        if (empty($this->_randomPassword) || '' == $this->_randomPassword) {
            $randomPassword = '';
            while ($i < $this->_getAttrib('passwordLength')) {
                mt_srand((double)microtime() * 1000000);
                // random limits within ASCII table
                $randNum = mt_rand($this->_getAttrib('lowerAsciiBound'), $this->_getAttrib('upperAsciiBound'));
                if (!in_array ($randNum, $this->_notUse)) {
                    $randomPassword = $randomPassword . chr($randNum);
                    $i++;
                }
            }
            // check password against regex
            $validator = new Zend_Validate_Regex($this->_getAttrib('regex'));
            if ($validator->isValid($randomPassword)) {
                $this->_randomPassword = $randomPassword;
            } else {
                $this->_randomPassword = '';
                $this->_createRandomPassword();
            }
        }
    }

    /**
     * Returns the random password
     *
     * @return string random password
     */
    public function getRandomPassword()
    {
        $this->_createRandomPassword();
        return $this->_randomPassword;
    }

    /**
     * Returns a single password option
     *
     * @param  string $key Password option to return
     * @return string Password option
     */
    protected function _getAttrib($key)
    {
        if (key_exists($key, $this->_attribs)) {
            return $this->_attribs[$key];
        }
    }

    /**
     * Returns all password options
     *
     * @return string All password options
     */
    protected function _getAttribs()
    {
        return $this->_attribs;
    }

    /**
     * Sets a single password option
     *
     * @param  string $key   Password option to set
     * @param  string $value Password option's value
     * @return void
     */
    protected function _setAttrib($key, $value)
    {
        if (key_exists($key, $this->_attribs) && !empty($value) && '' != $value) {
            $this->_attribs[$key] = $value;
        }
    }

    /**
     * Sets all password options
     *
     * @param  array  $attribs Array with password options
     * @return void
     */
    protected function _setAttribs($attribs = array())
    {
        foreach ($attribs as $key => $value) {
            $this->_setAttrib($key, $value);
        }
    }
}