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
class Digitalus_Validate_UsernameExists extends Zend_Validate_Abstract
{
    const NOT_EXISTS = 'notUsernameExists';

    protected $_messageTemplates = array(
        self::NOT_EXISTS => "The given user name %s doesn't exist in the database",
    );

    /**
     * usernames to be excluded from test
     *
     * @var string
     */
    protected $_exclude = array();

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
            if (array_key_exists('exclude', $options)) {
                $this->setExclude($options['exclude']);
            }
        }
    }

    /**
     * Returns the exclude option
     *
     * @return string
     */
    public function getExclude()
    {
        return $this->_exclude;
    }

    /**
     * Sets the exclude option
     *
     * @param  array/string $name
     * @return Digitalus_Validate_UsernameExists Provides a fluent interface
     */
    public function setExclude($name = null)
    {
        if (!is_array($name)) {
            $this->_exclude = array($name);
        } else {
            $this->_exclude = $name;
        }
    }

    public function isValid($value)
    {
        $value = (string)$value;
        $this->_setValue($value);

        $mdlUser = new Model_User();
        if ($mdlUser->userExists($value, $this->getExclude())) {
            return true;
        }
        $this->_error(self::NOT_EXISTS);
        return false;
    }
}