<?php
require('./application/modules/news/models/Category.php');
require('./application/modules/news/models/Item.php');

class Mod_News_IndexController extends Zend_Controller_Action 
{
	
	public function init()
	{
		$this->view->adminSection = 'module';
	}
	
	public function indexAction()
	{
		$item = new NewsItem();
		$this->view->recentItems = $item->getRecent();
	}

}