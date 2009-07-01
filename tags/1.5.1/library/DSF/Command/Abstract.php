<?php

/**
 * DSF CMS
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
 * @category   DSF CMS
 * @package   DSF_Core_Library
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: Abstract.php Tue Dec 25 19:55:23 EST 2007 19:55:23 forrest lyman $
 */

abstract class DSF_Command_Abstract
{
    protected $_log = array();

    /**
     * if run is not overloaded by the class that
     * extends this it is seen as an error
     *
     */
    public function run()
    {
        $this->log('Invalid command');
    }

    /**
     * add the message to the log stack
     *
     * @param string $message
     */
    public function log($message)
    {
        $this->_log[] = $message;
    }

    /**
     * returns the current log stack
     *
     * @return array
     */
    public function getResponse()
    {
        return $this->_log;
    }
}