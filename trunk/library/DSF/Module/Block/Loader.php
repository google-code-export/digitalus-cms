<?php
class DSF_Module_Block_Loader
{
	
	private $_blockData;
	private $_module;
	private $_block;
	private $_controllerClass;
	public $view;
	private $_properties;
		
	private $_adapter = 'Xml';
	
	public function __construct($block, $adapter = null)
	{
		if(null != $adapter) {
			$this->_setAdapter($adapter);
		}
		
		$adapter = $this->_getAdapter();
		$adapter->load($block);
		
		$this->_properties = $adapter->getProperties();		
		
		$this->setModule();
		$this->setBlock();
		$this->setView();
		$this->setController();
	}
	
	private function _setAdapter($adapter)
	{
		$this->_adapter = $adapter;	
	}
	
	private function _getAdapter()
	{
		$adapterClass = "DSF_Module_Block_Loader_Adapter_" . $this->_adapter;
		return new $adapterClass();
	}
	
	public function setModule()
	{
		$parts = explode('_', $this->_properties->type);
		$this->_module = $parts[0];
	}
	
	public function getModule()
	{
		return $this->_module;
	}
	
	public function setBlock()
	{
		$parts = explode('_', $this->_properties->type);
		$this->_block = $parts[1];		
	}
	
	public function getBlock()
	{
		return $this->_block;
	}
	
	protected function _getPath()
	{
		
		//load the front controller to get the module paths
		$front = Zend_Controller_Front::getInstance();
		$modulePaths = $front->getControllerDirectory();
		
		if(isset($modulePaths['mod_' . strtolower($this->_module)])) {
			$controllerPath = $modulePaths['mod_' . strtolower($this->_module)];
			return str_replace('controllers','blocks', $controllerPath) . '/' . $this->_block;
		}
	}
	
	public function setView()
	{
		$path = $this->_getPath();
		$this->view = new Zend_View();
		$this->view->addScriptPath($path);
		$this->view->addHelperPath($path . '/helpers');
	}
	
	public function setController()
	{
		$className = ucfirst($this->_module) . '_Block_' . ucfirst($this->_block) . '_Controller';
		$path = $this->_getPath();
		$blockPath = $path . '/controller.php';
		require_once($blockPath);
		$this->_controllerClass = new $className($this->_properties, $this->view);
	}
	
	public function getController()
	{
		return $this->_controllerClass;
	}
}