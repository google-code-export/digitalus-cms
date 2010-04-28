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
 * @version     $Id: List.php Tue Dec 25 20:32:04 EST 2007 20:32:04 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

class Digitalus_Data_List
{
    /**
     * the list of items
     *
     * @var std class
     */
    public $items;

    /**
     * you can pass this a serialized list (list->toString())
     * otherwise it will create an empty list
     *
     * @param string $data
     */
    public function __construct($data = null)
    {
        if ($data) {
            $this->items = unserialize($data);
        } else {
            $this->items = new stdClass();
        }
    }

    /**
     * add a new item
     *
     * @param string $key
     * @param string $value
     */
    public function addItem($key, $value)
    {
        $this->items->$key = $value;
    }

    /**
     * add a new group
     * the group is a new list
     *
     * @param string $key
     * @return Digitalus_Data_list
     */
    public function addGroup($key)
    {
        $newList = new Digitalus_Data_List();
        $this->items->$key = $newList;
        return $newList;
    }

    /**
     * remove a group
     *
     * @param string $key
     */
    public function removeGroup($key)
    {
        unset($this->items->$key);
    }

    /**
     * validates that a group exists
     *
     * @param string $key
     * @return bool
     */
    public function hasGroup($key)
    {
        if (isset($this->items->$key)) {
            return true;
        }
    }

    /**
     * serializes the lists items
     *
     * @return string
     */
    public function toString()
    {
        return serialize($this->items);
    }

}