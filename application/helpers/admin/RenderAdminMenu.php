<?php
class DSF_View_Helper_Admin_RenderAdminMenu
{
    public $sections = array(
        'index'         =>  'index',
        'site'          =>  'site',
        'report'        =>  'site',
        'user'          =>  'site',
        'page'          =>  'page',
        'navigation'	=>	'navigation',
        'media'	        =>	'media',
        'design'		=>	'design',
        'module'        =>  'module' 
    );
    public $defaultSection = 'index';
    public $selectedSection;
    
    public $userModel;
    public $currentUser;
  
	public function RenderAdminMenu($selectedItem = null, $id = 'adminMenu')
	{
		$this->userModel = new User();
        $this->currentUser = $this->userModel->getCurrentUser();
        
        $this->setSelectedSection();
        
        $menu = "<ul id='{$id}'>";
        
        if(!$this->currentUser){
        	$menu .= "<li class='med'><a href='{$this->view->baseUrl}/admin/auth/login' id='loginLink' class='selected'>" . $this->view->GetTranslation('Login') . "</a></li>";
        }else{
	        if($this->hasAccess('admin_index')){
	        	$menu .= "<li class='small'><a href='{$this->view->baseUrl}/admin' id='homeLink'" . $this->isSelected('index') . ">" . $this->view->GetTranslation('Home') . "</a></li>";
	        }
	        
	        if($this->hasAccess('admin_site')){
	        	$menu .= "<li class='small'><a href='{$this->view->baseUrl}/admin/site' id='siteLink'" . $this->isSelected('site') . ">" . $this->view->GetTranslation('Site') . "</a></li>";
	        }
	        
	        if($this->hasAccess('admin_page')){
	        	$menu .= "<li class='med'><a href='{$this->view->baseUrl}/admin/page' id='pageLink'" . $this->isSelected('page') . ">" . $this->view->GetTranslation('Pages') . "</a></li>";
	        }
	        
	        if($this->hasAccess('admin_navigation')) {
	        	$menu .= "<li class='large'><a href='{$this->view->baseUrl}/admin/navigation' id='navigationLink'" . $this->isSelected('navigation') . ">" . $this->view->GetTranslation('Navigation') . "</a></li>";
	        }
	        
	        if($this->hasAccess('admin_media')) {
	        	$menu .= "<li class='med'><a href='{$this->view->baseUrl}/admin/media' id='mediaLink'" . $this->isSelected('media') . ">" . $this->view->GetTranslation('Media') . "</a></li>";
	        }
	        
	        if($this->hasAccess('admin_design')) {
	        	$menu .= "<li class='med'><a href='{$this->view->baseUrl}/admin/design' id='designLink'" . $this->isSelected('design') . ">" . $this->view->GetTranslation('Design') . "</a></li>";
	        }
	        
	        if($this->hasAccess('admin_module')) {
	        	$menu .= "<li class='med'><a href='{$this->view->baseUrl}/admin/module' id='moduleLink'" . $this->isSelected('module') . ">" . $this->view->GetTranslation('Modules') . "</a></li>";
	        }
        }
      
    
        
        $menu .= "</ul>";
        
        return $menu;

	}

	public function isSelected($tab) {
	    if($tab == $this->selectedSection){
	        return " class='selected'";
	    }
	}
	
	public function setSelectedSection()
	{
	    $front = Zend_Controller_Front::getInstance();
	    $request = $front->getRequest();
	    $controller = $request->getControllerName();

	    if(isset($this->sections[$controller])){
	        $this->selectedSection = $this->sections[$controller];
	    }else{
	        $this->selectedSection = $this->defaultSection;
	    }
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
    
    public function hasAccess($tab){
    	if($this->currentUser){
	    	if($this->currentUser->role == user::SUPERUSER_ROLE) {
	    		return true;
	    	}elseif($this->userModel->queryPermissions($tab)){
	    		return true;
	    	}
    	}
    }
    
}