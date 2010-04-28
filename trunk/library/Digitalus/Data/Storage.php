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
 * @author      Forrest Lyman
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_Data
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: Storage.php Tue Dec 25 20:33:46 EST 2007 20:33:46 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

class Digitalus_Data_Storage
{
    /**
     * the storage object for the data
     *
     * @var Zend_Session_Namespace
     */
    protected $_storage;

    /**
     * set the storage
     *
     */
    public function __construct()
    {
        $this->_storage = new Zend_Session_Namespace('dataStorage');
    }

    /**
     * set the data
     *
     * @param array $data
     */
    public function set($data)
    {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $array[$k] = $v; //make sure we dont try to store an object in the session
            }
            $this->_storage->data = $array;
        }
    }

    /**
     * save the current post array
     *
     */
    public function savePost()
    {
        $this->set($_POST);
    }

    /**
     * returns the saved data
     * if persist false this deletes the data from the storage
     *
     * @param bool $persist
     * @return array
     */
    public function get($persist = false)
    {
        if (!empty($this->_storage->data)) {
            $data = new stdClass();
            foreach ($this->_storage->data as $k => $v) {
                $data->$k = $v;
            }
            if (!$persist) {
                Zend_Session::namespaceUnset('dataStorage');
            }
            return $data;
        }
    }
}