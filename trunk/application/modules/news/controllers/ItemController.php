<?php
require('./application/modules/news/models/Category.php');
require('./application/modules/news/models/Item.php');

class Mod_News_ItemController extends Zend_Controller_Action 
{
    public $pageLength = 10;
	
	public function init()
	{
		$this->view->adminSection = 'module';
	}
	
	public function indexAction()
	{
		$item = new NewsItem();
		$this->view->recentItems = $item->getRecent();
		$count = $item->countItems();
		$this->view->pages = ceil($count / $this->pageLength);
		$currentPage = $this->_request->getParam('page', 1);
		$this->view->items = $item->getItems($this->pageLength, $currentPage);
		$this->view->currentPage = $currentPage;
	}
	/**
	 * add a new item
	 *
	 */
	
	public function addAction()
	{
	    if($this->_request->isPost())
	    {
	        $p = new NewsItem();
	        $item = $p->insertFromPost();
	        if($item)
	        {
	            $url = '/mod_news/item/edit/id/' . $item->id;
	        }else{
	           $url = '/mod_news/index';
	        }
	    }
        $this->_redirect($url);
	}
	
	/**
	 * edit an existing item
	 * 
	 */
	public function editAction()
	{
        $p = new NewsItem();
		if($this->_request->isPost())
	    {
			//hack for mna
			$_POST['publish_date'] = str_replace('.','/',$_POST['publish_date']);
	        $item = $p->updateFromPost();
			$id = $item->id;
			
			//update the categories
			$p->setCategories($id, DSF_Filter_Post::raw('categories'));
   		
	    }else{
			$id = $this->_request->getParam('id', 0);
	    }
		$this->view->data = $p->find($id)->current();
		$this->view->items = $p->getRecent();
	}
	
	/**
	 * delete a item
	 */
	public function deleteAction()
	{
		//get the id
		$id = $this->_request->getParam('id', 0);
		
		//if the id is valid
		if($id > 0)
		{
		    $cat = new NewsItem();
   		
    	    //delete the state
		    $cat->delete('id = ' . $id);
		    $m = new DSF_View_Message();
		    $m->add('Your item was removed.');
		}else{
		    $e = new DSF_View_Error();
		    $e->add("There was an error removing your item");
		}
		$url = "/mod_news/item";
		$this->_redirect($url);
	   
	}
}