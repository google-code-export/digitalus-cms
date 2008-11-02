<?php
class DSF_View_Helper_Admin_RenderSidebar
{
    public $sections = array(
        'index'         =>  'index',
        'site'          =>  'site',
        'report'        =>  'site',
        'user'          =>  'site',
        'page'          =>  'page',
        'design'		=>	'design',
        'module'        =>  'module' 
    );
    public $defaultSection = 'index';
    public $selectedSection;
    public $sidebarPath;
    public $defaultHeadline = "Home";
  
    /**
     * this helper renders the admin sidebar.
     * 
     * you can override the header by setting: view->language->sidebar_headline
     * 
     * you can add content before the body by setting sidebar_before placeholder
     * you can add content after the body by setting sidebar_after placeholder
     *
     * @param unknown_type $selectedItem
     * @param unknown_type $id
     * @return unknown
     */
	public function RenderSidebar($selectedItem = null, $id = 'Sidebar')
	{
        $this->setSidebarPath();
        
       //load the content first so you can set the headline in the sidebar
        $xmlContent = $this->renderBody();
        $xmlHeadline = $this->renderHeadline();
        
        return $xmlHeadline . $xmlContent;
	}
	
	public function renderHeadline()
	{
    	$strHeadline = $this->view->placeholder("sidebarHeadline");
        if(empty($strHeadline)) {
            $strHeadline = $this->defaultHeadline;
        }
	    return "<h2 class='top'>" . $strHeadline . "</h2>";
	}
	
	public function renderBody()
	{
	    $xhtml = "<div class='columnBody'>";
	    
	    //you can add content before the body by setting sidebar_before placeholder
        $xhtml .= $this->view->placeholder('sidebar_before');
	    
	    $xhtml .= $this->view->render($this->sidebarPath);

	    //you can add content after the body by setting sidebar_after placeholder
        $xhtml .= $this->view->placeholder('sidebar_after');
	    
        $xhtml .= "</div>";
	    return $xhtml;
	}
	
	public function setSidebarPath()
	{
	    $front = Zend_Controller_Front::getInstance();
	    $request = $front->getRequest();
	    $controller = $request->getControllerName();

	    if(isset($this->sections[$controller])){
	        $this->selectedSection = $this->sections[$controller];
	    }else{
	        $this->selectedSection = $this->defaultSection;
	    }
	    
	    
        $this->sidebarPath = $this->selectedSection . '/sidebar.phtml';
	}
	
    /**
     * Set this->view object
     *
     * @param  Zend_this->view_Interface $this->view
     * @return Zend_this->view_Helper_DeclareVars
     */
    public function setview(Zend_view_Interface $view)
    {
        $this->view = $view;
        return $this;
    }
}
