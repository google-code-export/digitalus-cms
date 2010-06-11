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
 * @subpackage  Digitalus_Db
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5
 */

/**
 * Digitalus DB Table
 *
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5
 */
class Digitalus_Db_Table extends Zend_Db_Table_Abstract
{
    public    $view;
    protected $_data;
    protected $_errors;
    private   $_action;

    public function __construct($config = array())
    {
        $this->setView();
        $this->_name = self::getTableName($this->_name);
        parent::__construct($config);
    }

    public function getView()
    {
        return $this->view;
    }

    public function setView(Zend_View $view = null)
    {
        if ($view == null) {
            $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
            if (null === $viewRenderer->view) {
                $viewRenderer->initView();
            }
            $this->view = $viewRenderer->view;
        } else {
            $this->view = $view;
        }
    }

    public function insertFromPost()
    {
        $this->_loadPost();
        //try to run the before method
        if (method_exists($this, 'before')) {
            $this->before();
        }
        if (method_exists($this, 'beforeInsert')) {
            $this->beforeInsert();
        }
        $this->validateData();
        if (!$this->_errors->hasErrors()) { //there were no errors validating the data
            //since this is a insert lets set the id to null
            unset($this->_data['id']);
            $id = $this->insert($this->_data);

            //try to run the after method
            if (method_exists($this, 'after')) {
                $this->after($id);
            }
            return $this->find($id)->current(); //i like to return the whole data object
        }
    }

    public function updateFromPost()
    {
        $this->_action = 'update';
        $this->_loadPost();
        //try to run the before method
        if (method_exists($this, 'before')) {
            $this->before();
        }
        if (method_exists($this, 'beforeUpdate')) {
            $this->beforeUpdate();
        }
        $this->validateData();
        $id = $this->_data['id'];
        unset($this->_data['id']);
        if (!$this->_errors->get()) { //there were no errors validating the data

            $this->update($this->_data, 'id=' . $id);

            //try to run the after method
            if (method_exists($this, 'after')) {
                $this->after($id);
            }
        }
        return $this->find($id)->current(); //i like to return the whole data object
    }

    /**
     * this method assumes you have registered the post data
     * it loads each of the fields from the current table and sets
     * the data hash with the unvalidated data
     *
     */
    private function _loadPost()
    {
        foreach ($this->_cols as $col) {
            if (Digitalus_Filter_Post::has($col)) {
                $this->_data[$col] = Digitalus_Filter_Post::raw($col);
            }
        }
    }

    /**
     * this method takes the rawData hash and validates it according to the
     * rules you set in the model. this is all very simplistic by design.
     *
     * set the validation rules as parameters of the model
     *
     * $required = required fields
     *
     * $text = strip tags
     *
     * $rawText = does not strip tags
     *
     * $number = numeric
     *
     * $email = valid email
     *
     * $password = takes three parameters, the password, length, and password confirm.  if confirm
     * is set then it validates that the two are equal
     *
     * $date = converts the date to a timestamp
     *
     *
     */
    public function validateData()
    {
        $this->_errors = new Digitalus_View_Error();
        $validations = array('Required', 'Text', 'Integer', 'Number', 'Email', 'Password', 'Date', 'HTML', 'Unique');
        foreach ($validations as $v) {
            $validateFunction = '_validate' . $v;
            $this->$validateFunction();
        }
    }

    /**
     * sets the key's value to now (uses the timestamp)
     *
     * @param string $key
     */
    public function equalsNow($key)
    {
        $date = new Zend_Date();
        $this->_data[$key] = $date->get();
    }

    /**
     * sets the selected key to the value
     *
     * @param string $key
     * @param mixed $value
     */
    public function equalsValue($key, $value)
    {
       $this->_data[$key] = $value;
    }

    /**
     * gets the value of the key
     */
    public function getValue($key)
    {
        return $this->_data[$key];
    }

    /**
     * validates that each key in the required array exists
     *
     */
    private function _validateRequired()
    {
        if ($this->_required) {
            foreach ($this->_required as $r) {
                if ($this->_data[$r] == '') {
                    $this->_errors->add('The ' . $this->_getNiceName($r) . ' is required.');
                }
            }
        }
    }

    private function _validateHTML()
    {
        if ($this->_HTML) {
            foreach ($this->_HTML as $f) {
                //you must strip slashes first, as the HTML editors add them
                //by doing this you are able to process both raw HTML and WYSIWYG HTML
                if (isset($this->_data[$f])) {
                    $this->_data[$f] = addslashes(stripslashes($this->_data[$f]));
                }
            }
        }
    }

    private function _validateUnique()
    {
        if ($this->_unique) {
            //first get the original data if this is an update
            if ($this->_action == 'update') {
                $curr = $this->find($this->_data['id']);
            }

            foreach ($this->_unique as $f) {
                //if this is an update then confirm that the field has changed
                if (($this->_action == 'update' && $curr->$f != $this->_data[$f])||$this->_action != 'update') {
                    //note that this method is the last to run, so the data is already validated as secure
                    $rows = $this->fetchAll($f . " LIKE '{$this->_data[$f]}'");
                    if ($rows->count() > 0) {
                        $this->_errors->add('The ' . $this->_getNiceName($f) . ' ' . $this->_data[$f] . ' already exists.');
                    }
                }
            }
        }
    }

    /**
     * strips the tags from each key in the text array
     *
     */
    private function _validateText()
    {
        $filter = new Zend_Filter_StripTags();
        if ($this->_text) {
            foreach ($this->_text as $t) {
                if (isset($this->_data[$t])) {
                    $this->_data[$t] = $filter->filter($this->_data[$t]);
                }
            }
        }
    }

    /**
     * throws an error if any of the fields are not valid numbers
     *
     */
    private function _validateNumber()
    {
        if ($this->_number) {
            $validator = new Zend_Validate_Float();
            foreach ($this->_number as $n) {
                if (!$validator->isValid($this->_data[$n])) {
                    $this->_errors->add('The ' . $this->_getNiceName($n) . ' must be a valid number.');
                }
            }
        }
    }

    private function _validateInteger()
    {
        if ($this->_integer) {
            foreach ($this->_integer as $n) {
                if (!is_integer($this->_data[$n])) {
                    $this->_errors->add('The ' . $this->_getNiceName($n) . ' must be a valid integer.');
                }
            }
        }
    }

    /**
     * throws an error if the email fields are not valid email addresses
     *
     */
    private function _validateEmail()
    {
        if ($this->_email) {
            $validator = new Zend_Validate_EmailAddress();
            foreach ($this->_email as $e) {
                if (!$validator->isValid($this->_data[$e])) {
                    $this->_errors->add('The ' . $this->_getNiceName($e) . ' must be a valid email address.');
                }
            }
        }
    }

    /**
     * throws and error if the password is less than the set length
     * also throws an error if the password does not match the confirmation
     * finishes up by encrypting the password
     *
     */
    private function _validatePassword()
    {
        if ($this->_password) {
            if (strlen($data[$this->_password[0]] < $this->_password[1])) {
                $this->_errors->add('Your password must be at least ' . $this->_password[1] . ' characters in length.');
            }

            if ($this->_data[$this->_password[2]]) {
                if ($data[$this->_password[0]] != $data[$this->_password[2]]) {
                    $this->_errors->add('Your passwords do not match.');
                }
            }

            $data[$this->_password[0]] = libEncrypt::encryptData($data[$this->_password[0]]);
        }
    }

    /**
     * converts all date fields to timestamps
     *
     */
    private function _validateDate()
    {
        if ($this->_date) {
            foreach ($this->_date as $d) {
                if ($this->_data[$d] != '') {
                    $date = new Zend_Date($this->_data[$d]);
                    $this->_data[$d] = $date->get(Zend_Date::TIMESTAMP);
                }
            }
        }
    }

    /**
     * returns a human friendly version of the field name
     *
     * @param string $field
     * @return string
     */
    private function _getNiceName($field)
    {
        return str_replace('_', ' ', $field);
    }

    /**
     * returns a string containing a table name and a prefix
     *
     * @param  string  $tableName  Given "raw" table name
     * @param  string  $prefix     Optionally given table prefix
     * @param  string  $separator  Optionally given separator
     * @return string  modified table name
     */
    public static function getTableName($tableName, $prefix = null, $separator = '')
    {
        if (!isset($prefix) || empty($prefix)) {
            $registry = Zend_Registry::getInstance();
            if ($registry->isRegistered('config') && $config = $registry->get('config')) {
                $prefix = $config->database->prefix;
            } else {
                $prefix = '';
            }
        }
        $name = $prefix . $separator . $tableName;

        return (string) $name;
    }

    /**
     * checks whether a given table exists
     * returns a table description for a given tableName
     *
     * @param  string  $tableName  Given table name
     * @return boolean|array  table description or false if table doesn't exist
     */
    public static function tableExists($tableName)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        try {
            $tableDescription = $db->describeTable($tableName);
        } catch (Zend_Db_Statement_Exception $e) {
            return false;
        }
        return $tableDescription;
    }

    /**
     * checks whether a given column exists in a given table
     *
     * @param  string  $tableName  Given table name
     * @param  string  $columnName Given column name
     * @return boolean
     */
    public static function columnExists($tableName, $columnName)
    {
        if ($tableDescription = self::tableExists($tableName)) {
            if (!isset($tableDescription[$columnName]) || empty($tableDescription[$columnName])) {
                return false;
            }
        } else {
            throw new Digitalus_Db_Exception($this->view->getTranslation("The given table doesn't exist") . ':' . $tableName);
        }
        return true;
    }

    /**
     * returns the primary index
     *
     * @return string
     */
    public function getPrimaryIndex()
    {
        return $this->_primary;
    }
}