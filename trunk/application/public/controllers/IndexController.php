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
	}
	
    public function indexAction()
    {
        // create the new page object
        $this->page = Digitalus_Builder::loadPage(null, 'initialize.xml');
        
        // load the data
        Digitalus_Builder::loadPage(null, 'load_data.xml', $this->page);

        // load the view
        Digitalus_Builder::loadPage(null, 'load_view.xml', $this->page, $this->view);
        // render the page
        $this->view->page = $this->page;
        $this->view->layout()->page = $this->page->getParam('xhtml');
    }
}


