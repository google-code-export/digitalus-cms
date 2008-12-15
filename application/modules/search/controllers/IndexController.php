<?php

class Mod_Search_IndexController extends Zend_Controller_Action 
{  
	
	
	public function init()
	{  
	    $this->view->breadcrumbs = array(
	       'Modules' =>   $this->getFrontController()->getBaseUrl() . '/admin/module',
	       'Search' =>   $this->getFrontController()->getBaseUrl() . '/mod_search'
	    ); 
	    $this->view->toolbarLinks[$this->view->GetTranslation('Add to my bookmarks')] = $this->getFrontController()->getBaseUrl() . '/admin/index/bookmark/url/mod_search';
	    
	}
	  
    public function indexAction()
    {
    }
}