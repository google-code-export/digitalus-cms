<?php
/**
 * these are just samples!
 *
 */
class Wrapper extends DSF_Interface_Grid 
{
	public $columns = 16;
	public function init()
	{
		$top = $this->addElement('top', 16);
		    $subscribeLink = $top->addElement('subscribe_now', 6);
		    $subscribeLink->setAttribute('first', true);
		    $subscribeLink->setContent(
		      $this->view->render('sublayouts/common/top/subscribe_now.phtml')
		    );
		    $quickSearch = $top->addElement('quick_search', 6);
		    $quickSearch->setContent(
              $this->view->render('sublayouts/common/top/quick_search.phtml')
            );
		    $quickSearch->setAttribute('before', 4);
		    $quickSearch->setAttribute('last', true);
        
	        $nav = $top->addElement('main_nav', 16);
	        $nav->setContent($this->view->RenderMenu());
	        $nav->setAttribute('clear', true);
        
		$top->setAttribute('clear', true);
		
		$subnav = $this->addElement('sub_nav', 16);
		$subnav->setContent($this->view->RenderSubmenu());
		$subnav->setAttribute('clear', true);
		
		$page = $this->addElement('page', 16);
		$page->setAttribute('clear', true);
		
		$footerNav = $page->addElement('footer_nav', 16);
		$footerNav->setContent(
           $this->view->render('sublayouts/common/bottom/nav.phtml')
         );
		$footerNav->setAttribute('clear', true);
		
		$footerLinks = $page->addElement('footer_links', 16);
		$footerLinks->setContent(
           $this->view->render('sublayouts/common/bottom/links.phtml')
         );
		$footerLinks->setAttribute('clear', true);
		
		$boilerplate = $page->addElement('boilerplate', 16);
		$boilerplate->setContent(
           $this->view->render('sublayouts/common/bottom/boilerplate.phtml')
         );
		$boilerplate->setAttribute('clear', true);
	}
	
}
?>