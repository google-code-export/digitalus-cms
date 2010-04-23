<?php
abstract class Digitalus_Builder_Abstract extends Digitalus_Abstract
{
    protected $_page;
    protected $_attributes = array();

    public function __construct(Digitalus_Page $page = null, $attributes = array(), $persistPage = true)
    {
        parent::__construct();

        if ($page == null) {
            $page = new Digitalus_Page();
        }
        if ($persistPage == true) {
            if (Zend_Registry::isRegistered('page')) {
                $this->_page = Zend_Registry::get('page');
            } else {
                $this->_page = $page;
                $this->_registerPage();
            }
        } else {
            $this->_page = $page;
        }

        $this->_attributes = $attributes;

        //fire the init function
        $this->init();
    }

    public function init()
    {

    }

    public function getPage()
    {
        return $this->_page;
    }

    protected function _registerPage()
    {
        Zend_Registry::set('page', $this->_page);
    }
}