<?php
class DSF_Builder_Action_Template extends DSF_Builder_Abstract 
{
	public function loadContentTemplate()
	{
		//get the view instance
		$view = $this->_page->getView();
		$view->addScriptPath('./application/contentTemplates');
		
		//get the page object and template
		$template = $this->_page->getContentTemplate();
		$content = $this->_page->getContent();
		
		$view->content = $this->_page->getContent();
		
		//render the content template
		$templateParts = explode('_',$template);
		$view->placeholder('content')->set($view->render($templateParts[0] . '/' . $templateParts[1] . '/template.phtml'));
	}
	
	
	public function loadDesign()
	{
		$data = $this->_page->getData();
		$designId = $data->design;
		
		$mdlDesign = new Design();
		$mdlDesign->setDesign($designId);
		
		$this->_page->setDesign($mdlDesign);
	}
	
	public function setStyles()
	{
		$design = $this->_page->getDesign();
		$styles = $design->getStylesheets();
		if(is_array($styles)) {
			$view = $this->_page->getView();
			foreach ($styles as $style) {
				$view->headLink()->appendStylesheet($style);			
			}
		}
	}
	
	public function setScripts()
	{
		$design = $this->_page->getDesign();
		$scripts = $design->getScripts();
		if(is_array($scripts)) {
			$view = $this->_page->getView();
			foreach ($scripts as $script) {
				$view->headScript()->appendFile($script);			
			}
		}
	}
	
	public function setTemplatePath()
	{
		$config = Zend_Registry::get('config');
		$template = $this->_page->getDesign()->getTemplate();
		$view = $this->_page->getView();		
		$view->addScriptPath('./' . $config->design->publicTemplates . '/' . $template);
		
	}
	
	public function renderLayout()
	{
		$layout = $this->_page->getDesign()->getLayout();
		$view = $this->_page->getView();
		$view->placeholder('layout')->set($view->render('layouts/' . $layout . '.phtml'));
	}
	
	public function renderTemplate()
	{
		$template = $this->_page->getDesign()->getTemplate();
		$view = $this->_page->getView();
		$view->placeholder('template')->set($view->render('index.phtml'));
	}
	
}