<?php
abstract class DSF_Module_Block_Controller
{
	protected $_properties;
	public $view;
	public $defaultView = "view.phtml";
	protected $_params;
	
	public function __construct($properties, $view)
	{		
		$this->_properties = $properties;
		
		$this->view = $view;
		$this->init();		
	}
	
	public function loadParams()
	{
		$tri = new DSF_Uri();
		$this->_params = $uri->getParams();
	}
	
	public function init()
	{
		
	}
	
	public function isPost()
	{
		if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
			return true;
		}
	}
	
	public function render($viewScript = null)
	{
		if($viewScript == null) {
			$viewScript = $this->defaultView;
		}
		return $this->view->render($viewScript);
	}
}