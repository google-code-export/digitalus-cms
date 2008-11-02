<?php
class Admin_DesignController extends Zend_Controller_Action 
{
	
	function init()
	{
	    $this->view->breadcrumbs = array(
	       'Design' =>   '/admin/design'
	    );
	}
	
	public function indexAction()
	{
		$mdlDesign = new Design();
		$this->view->designs = $mdlDesign->listDesigns();
		
		$this->view->breadcrumbs['Open Design: News'] = '#';
	    $this->view->toolbarLinks = array();
	    $this->view->toolbarLinks[$this->view->GetTranslation('Add to my bookmarks')] = '#';
	    $this->design = new stdClass();
	}
	
	public function createAction()
	{
		if($this->_request->isPost()) {
			// NOTE: we will turn this into a Zend_Form after were sure it will work this way
			$mdlDesign = new Design();
			$name = DSF_Filter_Post::get('name');
			$notes = DSF_Filter_Post::get('notes');
			$id = $mdlDesign->createDesign($name, $notes);
			$this->_redirect('admin/design/update/id/' . $id);
			return;
		}
		$this->_forward('index');		
	}
	
	public function updateAction()
	{
		$mdlDesign = new Design();
		$this->view->designs = $mdlDesign->listDesigns();
		
		if($this->_request->isPost()) {
			// NOTE: we will turn this into a Zend_Form after were sure it will work this way
			$id = DSF_Filter_Post::int('id');
			$mdlDesign->updateDesign(
				$id,
				DSF_Filter_Post::get('name'),
				DSF_Filter_Post::get('notes'),
				DSF_Filter_Post::get('layout'),
				DSF_Filter_Post::raw('styles'),				
				DSF_Filter_Post::get('inline_styles')
			);
		}else{
			$id = $this->_request->getParam('id');
		}
		
		$mdlDesign->setDesign($id);
		$mdlPage = new Page();
		$this->view->pages = $mdlPage->getPagesByDesign($id);
				
	    $this->view->breadcrumbs["Open: " . $mdlDesign->getValue('name')] = '/admin/design/edit/id/' . $id;
	    $this->view->toolbarLinks = array();
	    $this->view->toolbarLinks[$this->view->GetTranslation('Add to my bookmarks')] = '#';
	    $this->view->toolbarLinks[$this->view->GetTranslation("Delete")] = '/admin/design/delete/id/' . $id;
	    
		
		$this->view->design = $mdlDesign;
	}
	
	public function deleteAction()
	{
		$mdlDesign = new Design();
		$id = $this->_request->getParam('id');
		$mdlDesign->deleteDesign($id);
		$this->_forward('index');
	}
	
}