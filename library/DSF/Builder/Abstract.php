<?php
abstract class DSF_Builder_Abstract
{
    protected $_page;
    protected $_attributes = array();

    public function __construct(DSF_Page $page = null, $attributes = array(), $persistPage = true)
    {
    	if($page == null) {
    		$page = new DSF_Page();
    	}
        if($persistPage == true) {
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