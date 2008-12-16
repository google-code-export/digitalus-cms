<?php
class DSF_Builder_Action_Page extends DSF_Builder_Abstract 
{
	public function setUri()
	{
		$uri = new DSF_Uri();
		$this->_page->setUri($uri->toArray());
	}
	
	public function appendUriParams()
	{
		$uri = $this->_page->getUri();
		if(is_array($uri)) {
			$uriParts = DSF_Toolbox_Array::splitOnValue($uri, 'p');
			if(is_array($uriParts)) {
				$params = DSF_Toolbox_Array::makeHashFromArray($uriParts[1]);
				foreach ($params as $k => $v) {
					$this->_page->setParam($k, $v);
				}
			}
		}
	}
	
	public function setBaseUrl()
	{
	    $front = Zend_Controller_Front::getInstance();
	    $baseUrl = $front->getBaseUrl();
		$this->_page->setBaseUrl($baseUrl);
	}
	
	public function setPointer()
	{
		$mdlPage = new Page();
		$uri = $this->_page->getUri();
		$pointer = $mdlPage->fetchPointer($uri);
		$this->_page->setId($pointer);
	}
	
	public function loadData()
	{
		$mdlPage = new Page();
		$row = $mdlPage->find($this->_page->getId())->current();
		if($row) {
			$this->_page->setData($row);
		}
	}
	
	public function loadContent()
	{
 		$mdlContentNode = new ContentNode();
 		$content = $mdlContentNode->fetchContentObject($this->_page->getId());
 		$this->_page->setContent($content);
	}
	
	public function loadTranslation()
	{
 		$mdlContentNode = new ContentNode();
 		$translation = DSF_Language::getLanguage();
 		if(!empty($translation)) {
     		$content = $mdlContentNode->fetchContentObject($this->_page->getId(), null, null, $translation);
     		$this->_page->setTranslation($content);  
 		}
    }
	
	public function loadContentTemplate()
	{
		$pageData = $this->_page->getData();
		$contentTemplate = $pageData->content_template;
		$this->_page->setContentTemplate($contentTemplate);
	}
	
	public function loadMetaData()
	{
 		//load meta data
 		$mdlMeta = new MetaData();
 		$metaData = $mdlMeta->get($this->_page->getId());
 		$this->_page->setMeta($metaData);
	}
	
	public function loadProperties()
	{
 		$mdlProperties = new Properties();
 		$properties = $mdlProperties->get($this->_page->getId());
 		$this->_page->setProperties($properties);		
	}
	
	public function loadRelatedPages()
	{
		
	}
	
	public function loadAttachments()
	{
		
	}
	
	public function setTitle()
	{
		$view = $this->_page->getView();
		$pageId = $this->_page->getId();
		
		//load the base site name
		$mdlSettings = new SiteSettings();
		$siteName = $mdlSettings->get('name');
		$separator = $mdlSettings->get('title_separator');
		
		//load the current page title
		$mdlPage = new Page();
		$title = $mdlPage->getTitle($pageId);
		
		//set the title
		$view->headTitle($siteName);
		
		if(is_array($title)) {
			foreach ($title as $pageTitle) {
				$view->headTitle($pageTitle);
			}
		}
		
		$view->headTitle()->setSeparator($separator);
	}
	
	public function googleIntegration()
	{
		$view = $this->_page->getView();
		$settings = new SiteSettings();
		$view->placeholder('google_verify')->set($settings->get('google_verify'));
		$view->placeholder('google_tracking')->set($settings->get('google_tracking'));
	}
	
	public function registerViewHelpers()
	{
	    $view = $this->_page->getView();
    	$helperDirs = DSF_Filesystem_Dir::getDirectories('./application/helpers');
    	if(is_array($helperDirs))
    	{
    		foreach ($helperDirs as $dir) {
    			$view->addHelperPath('./application/helpers/' . $dir, 'DSF_View_Helper_' . ucfirst($dir));
    		}
    	}
	}
}