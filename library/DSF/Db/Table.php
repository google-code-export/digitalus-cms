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
 * @version    $Id: Table.php Tue Dec 25 20:37:43 EST 2007 20:37:43 forrest lyman $
 */

class DSF_Db_Table extends Zend_Db_Table
{
    protected $data;
    private  $errors;
    private $action;

    public function insertFromPost()
    {
        $this->loadPost();
        //try to run the before method
        if (method_exists($this,'before')) {
            $this->before();
        }
        if (method_exists($this,'beforeInsert')) {
            $this->beforeInsert();
        }
        $this->validateData();
        if (!$this->errors->hasErrors()) { //there were no errors validating the data
            //since this is a insert lets set the id to null
            unset($this->data['id']);
            $id = $this->insert($this->data);

            //try to run the after method
            if (method_exists($this,'after')) {
                $this->after($id);
            }
            return $this->find($id)->current(); //i like to return the whole data object
        }
    }

    public function updateFromPost()
    {
        $this->action = 'update';
        $this->loadPost();
        //try to run the before method
        if (method_exists($this,'before')) {
            $this->before();
        }
        if (method_exists($this,'beforeUpdate')) {
            $this->beforeUpdate();
        }
        $this->validateData();
        $id = $this->data['id'];
        unset($this->data['id']);
        if (!$this->errors->get()) { //there were no errors validating the data

            $this->update($this->data, "id=" . $id);

            //try to run the after method
            if (method_exists($this,'after')) {
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
    private function loadPost()
    {
        foreach ($this->_cols as $col) {
            if (DSF_Filter_Post::has($col)) {
                $this->data[$col] = DSF_Filter_Post::raw($col);
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
        $this->errors = new DSF_View_Error();
        $validations = array('Required','Text','Integer','Number','Email','Password','Date','HTML','Unique');
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
        $this->data[$key] = $date->get();
    }

    /**
     * sets the selected key to the value
     *
     * @param string $key
     * @param mixed $value
     */
    public function equalsValue($key, $value)
    {
       $this->data[$key] = $value;
    }

    /**
     * gets the value of the key
     */
    public function getValue($key)
    {
        return $this->data[$key];
    }

    /**
     * validates that each key in the required array exists
     *
     */
    private function _validateRequired()
    {
        if ($this->Required) {
            foreach ($this->Required as $r) {
                if ($this->data[$r] == '') {
                    $this->errors->add('The ' . $this->_getNiceName($r) . ' is required.');
                }
            }
        }
    }

    private function _validateHTML()
    {
        if ($this->HTML) {
            foreach ($this->HTML as $f) {
                //you must strip slashes first, as the HTML editors add them
                //by doing this you are able to process both raw HTML and WYSIWYG HTML
                if (isset($this->data[$f])) {
                    $this->data[$f] = addslashes(stripslashes($this->data[$f]));
                }
            }
        }
    }

    private function _validateUnique()
    {
        if ($this->Unique) {
            //first get the original data if this is an update
            if ($this->action =='update') {
                $curr = $this->find($this->data['id']);
            }

            foreach ($this->Unique as $f) {
                //if this is an update then confirm that the field has changed
                if (($this->action == 'update' && $curr->$f != $this->data[$f])||$this->action != 'update') {
                    //note that this method is the last to run, so the data is already validated as secure
                    $rows = $this->fetchAll($f . " LIKE '{$this->data[$f]}'");
                    if ($rows->count() > 0) {
                        $this->errors->add('The ' . $this->_getNiceName($f) . ' ' . $this->data[$f] . ' already exists.');
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
        if ($this->Text) {
            foreach ($this->Text as $t) {
                if (isset($this->data[$t])) {
                    $this->data[$t] = $filter->filter($this->data[$t]);
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
        if ($this->Number) {
            $validator = new Zend_Validate_Float();
            foreach ($this->Number as $n) {
                if (!$validator->isValid($this->data[$n])) {
                    $this->errors->add('The ' . $this->_getNiceName($n) . ' must be a valid number.');
                }
            }
        }
    }

    private function _validateInteger()
    {
        if ($this->Integer) {
            foreach ($this->Integer as $n) {
                if (!is_integer($this->data[$n])) {
                    $this->errors->add('The ' . $this->_getNiceName($n) . ' must be a valid integer.');
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
        if ($this->Email) {
            $validator = new Zend_Validate_EmailAddress();
            foreach ($this->Email as $e) {
                if (!$validator->isValid($this->data[$e])) {
                    $this->errors->add('The ' . $this->_getNiceName($e) . ' must be a valid email address.');
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
        if ($this->Password) {
            if (strlen($data[$this->Password[0]] < $this->Password[1])) {
                $this->errors->add('Your password must be at least ' . $this->Password[1] . ' characters in length.');
            }

            if ($this->data[$this->Password[2]]) {
                if ($data[$this->Password[0]] != $data[$this->Password[2]]) {
                    $this->errors->add('Your passwords do not match.');
                }
            }

            $data[$this->Password[0]] = libEncrypt::encryptData($data[$this->Password[0]]);
        }
    }

    /**
     * converts all date fields to timestamps
     *
     */
    private function _validateDate()
    {
        if ($this->Date) {
            foreach ($this->Date as $d) {
                if ($this->data[$d] != '') {
                    $date = new Zend_Date($this->data[$d]);
                    $this->data[$d] = $date->get(Zend_Date::TIMESTAMP);
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
        return str_replace('_',' ',$field);
    }

}
