<?php
class Model_Data extends Zend_Db_Table_Abstract
{
    protected $_name = 'data';

    public function __construct()
    {
        parent::__construct();
        $this->_name = Digitalus_Db_Table::getTableName($this->_name);
    }

}