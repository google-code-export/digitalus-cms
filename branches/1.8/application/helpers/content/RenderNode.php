<?php
/**
 *
 * @author forrest lyman
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * RenderNode helper
 *
 * @uses viewHelper Digitalus_View_Helper_Content
 */
class Digitalus_View_Helper_Content_RenderNode {
    
    /**
     * @var Zend_View_Interface 
     */
    public $view;
    
    /**
     *  
     */
    public function renderNode($uri, $node) {
        $page = new Model_Page();
        $content = $page->getContent($uri);
        return $content[$node];
    }
    
    /**
     * Sets the view field 
     * @param $view Zend_View_Interface
     */
    public function setView(Zend_View_Interface $view) {
        $this->view = $view;
    }
}
