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
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_View
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id$
 */

class Digitalus_View_Error
{
    /**
     * the storage
     *
     * @var zend_session_namespace
     */
    private $_ns;

    /**
     * the errors stack
     *
     * @var array
     */
    private $_errors;

    /**
     * set up the session namespace and load the errors stack
     *
     */
    public function __construct()
    {
        $this->_ns = new Zend_Session_Namespace('errors');
        if (isset($this->_ns->errors)) {
            $this->_errors = $this->_ns->errors;
        }
    }

    /**
     * clear the errors stack
     *
     */
    public function clear()
    {
        unset($this->_errors);
        $this->_updateNs();
    }

    /**
     * add an error to the stack
     *
     * @param string $error
     */
    public function add($error)
    {
        $this->_errors[] = $error;
        $this->_updateNs();
    }

    /**
     * check to see if any errors are set
     *
     * @return bool
     */
    public function hasErrors()
    {
        if (count($this->_errors) > 0) {
            return true;
        }
    }

    /**
     * get the errors stack
     *
     * @return string
     */
    public function get()
    {
        return $this->_errors;
    }

    /**
     * update the storage
     *
     */
    private function _updateNs()
    {
        if (isset($this->_errors)) {
            $this->_ns->errors = $this->_errors;
        } else {
            unset($this->_ns->errors);
        }
    }
}