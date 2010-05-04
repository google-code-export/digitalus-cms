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
class Digitalus_Validate_PagenameExistsNot extends Zend_Validate_Abstract
{
    const EXISTS   = 'pagenameExists';
    const ISADMIN  = 'isAdmin';
    const ISPUBLIC = 'isPublic';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::EXISTS   => "Another page with Your desired name '%value%' already exists! Please choose a different one!",
        self::ISADMIN  => "The page name must not be 'admin'! Please choose a different one!",
        self::ISPUBLIC => "The page name must not be 'public'! Please choose a different one!",
    );

    /**
     * pagenames to be excluded from test
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

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value doesn't exist already as page name
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $value = (string)$value;
        $this->_setValue($value);

        $mdlPage = new Model_Page();
        if ($mdlPage->pagenameExists($value, $this->getExclude())) {
            $this->_error(self::EXISTS);
            return false;
        }
        if ('admin' == strtolower($value)) {
            $this->_error(self::ISADMIN);
            return false;
        }
        if ('public' == strtolower($value)) {
            $this->_error(self::ISPUBLIC);
            return false;
        }
        return true;
    }
}