<?php
/**
 * the public index controller's sole mission in life is to render content pages
 *
 */
class IndexController extends Zend_Controller_Action
{
	public $page;
	public $cache;
	public $pageGUID;
	
	public function init()
	{
	    // set up the cache and get the guid for the current page
        $this->cache = Zend_Registry::get('cache');
        $uri = new DSF_Uri();
        $this->pageGUID = md5($uri->get(false, false));     // Include absolute path and params in the hash
	}
	
    public function indexAction()
    {
        $cachedPage = $this->cache->load($this->pageGUID);
        
        if($cachedPage) {
           $this->page = $cachedPage;
        }else{
            // create the new page object
            $this->page = DSF_Builder::loadPage(null, 'initialize.xml');
            
            // load the data
            DSF_Builder::loadPage(null, 'load_data.xml', $this->page);
            
            // save it to the cache
            $this->cache->save($this->page, $this->pageGUID);
        }
        
        // load the view
        DSF_Builder::loadPage(null, 'load_view.xml', $this->page, $this->view);
        
        // render the page
        $this->view->page = $this->page;
        $this->view->layout()->page = $this->view->render($this->page->getLayout());
    }
}


