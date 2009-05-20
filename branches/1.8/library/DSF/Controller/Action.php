<?php

class Digitalus_Controller_Action extends Zend_Controller_Action
{
    public $page;

    public function init()
    {
        $this->_helper->removeHelper('viewRenderer');
        if (Zend_Registry::isRegistered('page')) {
            $this->page = Zend_Registry::get('page');
        } else {
            $this->page = new Digitalus_Page();
            $this->_registerPage();
        }
    }

    protected function _registerPage()
    {
        Zend_Registry::set('page', $this->page);
    }

}