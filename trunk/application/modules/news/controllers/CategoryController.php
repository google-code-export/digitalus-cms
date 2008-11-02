<?php
require('./application/modules/news/models/Category.php');
require('./application/modules/news/models/Item.php');

class Mod_News_CategoryController extends Zend_Controller_Action 
{
	
	public function init()
	{
		$this->view->adminSection = 'module';
	}
	
	public function indexAction()
	{
		$cats = new NewsCategory();
		$this->view->categories = $cats->fetchAll(null, 'title');
	}
	/**
	 * add a new category
	 *
	 */
	
	public function addAction()
	{
	    if($this->_request->isPost())
	    {
	        $p = new NewsCategory();
	        $category = $p->insertFromPost();
	        if($category)
	        {
	            $url = '/mod_news/category/edit/id/' . $category->id;
	        }else{
	           $url = '/mod_news/index';
	        }
	    }
        $this->_redirect($url);
	}
	
	/**
	 * edit an existing category
	 * 
	 */
	public function editAction()
	{
        $p = new NewsCategory();
		if($this->_request->isPost())
	    {
	        $category = $p->updateFromPost();
			$id = $category->id;
	    }else{
			$id = $this->_request->getParam('id', 0);
	    }
		$this->view->data = $p->find($id)->current();
		$this->view->categories = $p->fetchAll(null, 'title');
	}
	
	/**
	 * delete a state
	 */
	public function deleteAction()
	{
		//get the id
		$id = $this->_request->getParam('id', 0);
		
		//if the id is valid
		if($id > 0)
		{
		    $cat = new NewsCategory();
   		
    	    //delete the state
		    $cat->delete('id = ' . $id);
		    $m = new DSF_View_Message();
		    $m->add('Your category was removed.');
		}else{
		    $e = new DSF_View_Error();
		    $e->add("There was an error removing your category");
		}
		$url = "/mod_news/category";
		$this->_redirect($url);
	   
	}
}