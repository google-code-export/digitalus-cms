<?php
/**
 * these are just samples!
 *
 */
require_once './application/public/views/grids/Wrapper.php';
class Home extends Wrapper 
{
	public function init()
	{
		parent::init();
		$page = $this->getElement('page');
	    $pageHeader = $page->addElement('page_header', 16);
           $pageHeader->setAttribute('clear', true);
           $feature = $page->addElement('feature', 11);
           $feature->setAttribute('first', true);
           $featuredAd = $page->addElement('featured_ad', 5);
           $featuredAd->setAttribute('last', true);
           $myShape = $page->addElement('my_shape', 3);
           $myShape->setAttribute('first', true);
           $innerPage = $page->addElement('inner_page', 13);
           $innerPage->setAttribute('last', true);
               $content = $innerPage->addElement('content', 8);
               $content->setContent('test');
               $sidebar = $innerPage->addElement('sidebar', 5);
               $sidebar->setAttribute('clear', true);
               $footerAds = $innerPage->addElement('footer_ad', 16);
               $footerAds->setAttribute('clear', true);
           $innerPage->setAttribute('clear', true);
	}
}
?>