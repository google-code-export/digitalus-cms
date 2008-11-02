<?php
abstract class DSF_Builder_Abstract
{
	protected $_page;
	
	public function __construct()
	{
		if(Zend_Registry::isRegistered('page')) {
			$this->_page = Zend_Registry::get('page');
		}else{
			$this->_page = new DSF_Page();
			$this->_registerPage();
		}
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