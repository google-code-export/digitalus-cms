<?php
class DSF_Builder_Action_Design extends DSF_Builder_Abstract 
{
	public function loadContentTemplate()
	{
		//get the view instance
		$view = $this->_page->getView();
		$view->addScriptPath('../application/contentTemplates');
		
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
		//the design model returns the stylesheets organized by skin
		$skins = $design->getStylesheets();
		if(is_array($skins)) {
			$view = $this->_page->getView();
			foreach ($skins as $skin => $styles) {
				if(is_array($styles)) {
					foreach ($styles as $style) {
						$view->headLink()->appendStylesheet($this->_page->getBaseUrl() . '/skins/' . $skin . '/styles/' . $style);	
					}
				}		
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
				$view->headScript()->appendFile($this->_page->getBaseUrl() . '/' . $script);			
			}
		}
	}
	
	public function setLayout()
	{
		$design = $this->_page->getDesign();
		$layout = $design->getLayout();
		$this->_page->setLayout($layout);
	}
	
	
	
	
}