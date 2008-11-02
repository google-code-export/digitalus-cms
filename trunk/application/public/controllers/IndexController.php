<?php
/**
 * the public index controller's sole mission in life is to render content pages
 *
 */
class IndexController extends Zend_Controller_Action
{
	public $page;
	
	public function init()
	{
		$view = new Zend_View();
		$this->_setParam('view',$view);
	}
	
	public function indexAction()
	{	
		DSF_Builder::loadPage();
		$this->page = DSF_Builder::getPage();
		$this->view->layout()->page = $this->view->render('layouts/' . $this->page->getLayout());
		
	}
}


