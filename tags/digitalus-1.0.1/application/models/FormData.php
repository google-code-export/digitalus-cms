<?php
class FormData extends Zend_Db_Table 
{
    protected $_name = "form_data";
    protected $_primary = "id";
    
    public function __construct($adapter)
    {
       parent::__construct(array('db' => $adapter));
    }
}