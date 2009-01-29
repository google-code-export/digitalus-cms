<?php

class DSF_Module {
	const MODULE_KEY = 'module';
	protected $_page;
	
	function __construct() {
	   $this->_page = DSF_Builder::getPage();
	}
	
	public function getData()
	{
		if(isset($this->_page)) {
			$content = $this->_page->getContent();
			if(is_array($content) && isset($content[self::MODULE_KEY])) {
				 return simplexml_load_string($content[self::MODULE_KEY]);
			}
		}
	}
}

?>