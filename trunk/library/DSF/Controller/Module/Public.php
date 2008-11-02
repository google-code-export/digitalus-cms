<?php

/**
 * this class sets up the public modules
 *
 */
class DSF_Controller_Module_Public extends Zend_Controller_Action 
{
    public function init()
    {
		$path = new DSF_Uri();		
		$page = new ContentPage();
		$page->setPage($path->toArray());
		$this->view->pageObj = $page;
    }
}