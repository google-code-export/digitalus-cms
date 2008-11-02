<?php
/**
 * the public index controller's sole mission in life is to render content pages
 *
 */
class IndexController extends Zend_Controller_Action
{
	protected $_config;
	private $_page;
	private $_content;
	protected $_layout;
	protected $_template;
	
	function init()
	{
	}
	
	/**
	 *
	 */
	function indexAction()
	{
		$this->loadPage();
		$this->setupView();
        $this->loadHeaders();
        $this->loadHead();
	    $this->loadDesign();
		$this->loadJquery();
		$this->renderView();
		$this->render('index');
	}
	
	public function loadPage()
	{
        $this->_config = Zend_Registry::get("config");
		$path = new DSF_Uri();		
		$this->_page = new ContentPage();
		$this->_page->setPage($path->toArray());
		
		$this->view->pageObj = $this->_page;
		
		//register the current page object for modules
		Zend_Registry::set('pageObj', $this->_page);
		
		$this->_content = $this->_page->getPage();
		$this->view->page = $this->_content;
		
	}
	
	public function setupView()
	{
		//$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		//$viewRenderer->setNoRender();
		
		//register the helpers the developer has added to the template
		$this->view->addHelperPath('./templates/public/' . $template . '/helpers');
		
		//set up modules
		$this->view->moduleAction = $_SERVER['REQUEST_URI'];
		
		//register all of the common  view helpers
        DSF_View_RegisterHelpers::register($this->view);
        
		$this->view->addScriptPath('./templates/public');
        
	}
	
	public function loadHeaders()
	{
	    $headers = $this->_page->getHeaders();
	    if($headers){
	        foreach ($headers as $header){
	            if(is_array($header)){
	                //to set a named header use an array ('key', 'value');
	                $this->_response->setHeader($header[0], $header[1]);
	            }else{
	                $this->_response->setRawHeader($header);
	            }
	            
	        }
	    }
	    
	    $responseCode = $this->_page->getResponseCode();
	    if($responseCode){
	        $this->_response->setHttpResponseCode($responseCode);
	    }
	    
   
	}
	
	public function loadHead()
	{
	    //google integration
	    $settings = new SiteSettings();
	    $trackingCode = $settings->get('google_tracking');
	    if(!empty($trackingCode)){
	        $this->view->placeholder('google_tracking')->set($trackingCode);
	    }
	    
	    $verificationTag = $settings->get('google_verify');
	    if(!empty($verificationTag)){
	        $this->view->placeholder('google_verify')->set($verificationTag);
	    }
	    
	    if(!empty($this->_content->meta_keywords)){
	        $this->view->headMeta()->appendName('keywords', $this->_content->meta_keywords);
	    }
	    
	    if(!empty($this->_content->meta_description)){
	        $this->view->headMeta()->appendName('description', $this->_content->meta_description);
	    }
	    
	}
	
	public function loadDesign()
	{
		$this->_layout = $this->_page->getLayout();
		$this->_template = $this->_page->getTemplate();
		
	    //load style sheets
	    $pathToStyles = "./templates/public/" . $this->_template . '/styles';
	    $absPathToStyles = "/templates/public/" . $this->_template . '/styles/';
	    $styles = DSF_Filesystem_File::getFilesByType($pathToStyles, 'css', $absPathToStyles);
	    
	    if(is_array($styles)){
	        foreach ($styles as $style){
	            $this->view->headLink()->appendStylesheet($style);
	        }
	    }
	    
	    //include the ajax editor css
	    $this->view->headLink()->appendStylesheet('/public/styles/ajaxEditor.css');
	    
	    //load user styles
	    $userStyles = $this->_page->getProperty('user_styles', 'design');
	    if($userStyles){
	        $this->view->headStyle()->appendStyle($userStyles);
	    }
	    
	}
	
	public function loadJquery()
	{
	    $this->view->headScript()->appendFile('/public/scripts/jquery-1.2.1.pack.js');
	    $this->view->headScript()->appendFile('/public/scripts/ThickBox.js');
	    $this->view->placeholder('jquery')->set('');
	}
	
	public function renderView()
	{
		//load the layout script
		$layout = $this->view->render($this->_template . '/layouts/' . $this->_layout . '.phtml');
		$this->view->placeholder('layout')->set($layout);
		
		//render the skin
		$template = $this->view->render($this->_template . '/index.phtml');
		$this->view->placeholder('template')->set($template);
	}
	
}


