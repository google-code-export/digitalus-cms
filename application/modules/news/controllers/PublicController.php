<?php
require('./application/modules/news/models/Category.php');
require('./application/modules/news/models/Item.php');

class Mod_News_PublicController extends DSF_Controller_Module_Public 
{
    /**
     * renders the new center view
     * 
     * @param int openCenter
     * @param int openItem
     *
     */
    public function newsCenterAction()
    {	
        $searchNs = new Zend_Session_Namespace('newsSearch');	
		$center = $this->_request->getParam('openCenter', 0);
		$item = $this->_request->getParam('openItem', 0);
		$search = $this->_request->getParam('search');
	    $c = new NewsCategory();
	    $i = new NewsItem();
	    
	    if($this->_request->isPost()){
	        //this request is a search
		    $keywords = DSF_Filter_Post::get('keywords');
		    $searchAll = DSF_Filter_Post::int('searchAll');
		    $searchNs->keywords = $keywords;
		    $searchNs->searchAll = $searchAll;
		    $searchNs->results = $i->search($keywords, $searchAll); 
		    $this->_redirect($_SERVER['REQUEST_URI'] . '/p/search/results');
	    }elseif($search == 'results'){
	        //display the saved search results
	        //this is done for useablity so people dont keep posting when they are going back to search results
		    $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		    $viewRenderer->setNoRender();
		    $this->view->keywords = $searchNs->keywords;
		    $this->view->searchAll = $searchNs->searchAll;
	        $this->view->searchResults = $searchNs->results;
		    echo $this->view->render('public/newscenter/searchresults.phtml');	        
	    
        }elseif($center > 0){
		    //open the news center
		    $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		    $viewRenderer->setNoRender(); 
		    $this->view->center = $c->find($center)->current();
		    $this->view->items = $i->getItemsByCategory($center); 
		    echo $this->view->render('public/newscenter/opencenter.phtml');
		}elseif ($item > 0){
		    $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		    $viewRenderer->setNoRender(); 
		    //open the news item
		    $this->view->item = $i->find($item)->current();
		    echo $this->view->render('public/newscenter/openitem.phtml');
		}else{
		    //display all news centers
		    $this->view->centers = $c->fetchAll(null, 'title');
		}
		
		
    }
    
    public function listAllAction()
    {
	    $i = new NewsItem();
		$item = $this->_request->getParam('openItem', 0);
		if($item > 0){
		    $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		    $viewRenderer->setNoRender(); 
		    //open the news item
		    $this->view->item = $i->find($item)->current();
		    echo $this->view->render('public/newscenter/openitem.phtml'); 
		}else{
		    $this->view->items = $i->getCurrent();
		}
    }
    
    public function listNewsByCategoryAction()
    {
        //this action reuses the views from the news center action
	    $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
	    $viewRenderer->setNoRender(); 
		    
	    $c = new NewsCategory();
	    $i = new NewsItem();
	    
	    //get the news category
        $id = $this->_request->getParam('news_category');
        $this->view->center = $c->find($id)->current();
        
        //get the items by category
	    $this->view->items = $i->getItemsByCategory($id); 
	    
	    //get the selected item if it is set
		$item = $this->_request->getParam('openItem', 0);
		if($item > 0){
		    //open the news item
		    $this->view->item = $i->find($item)->current();
		    echo $this->view->render('public/newscenter/openitem.phtml'); 
		}else{
		    //open the news center
		    echo $this->view->render('public/newscenter/opencenter.phtml');
		}
    }
    
    public function listNewsByYearAction()
    {
	    $i = new NewsItem();
	    $year = $this->_request->getParam('year');
		$item = $this->_request->getParam('openItem', 0);
		if($item > 0){
		    $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		    $viewRenderer->setNoRender(); 
		    //open the news item
		    $this->view->item = $i->find($item)->current();
		    echo $this->view->render('public/newscenter/openitem.phtml'); 
		}else{
		    $this->view->items = $i->getItemsByYear($year);
		}
		$this->view->year = $year;
    }
}