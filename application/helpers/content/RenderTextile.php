<?php
/**
 *
 * @author forrest lyman
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * RenderTextile helper
 *
 * @uses viewHelper DSF_View_Helper_Content
 */
class DSF_View_Helper_Content_RenderTextile {
    
    /**
     * @var Zend_View_Interface 
     */
    public $view;
    
    /**
     *  
     */
    public function renderTextile($content) {
        $content = stripslashes($content);
        $textile = new DSF_Content_Render_Textile();
        return $textile->TextileThis($content);
    }
    
    /**
     * Sets the view field 
     * @param $view Zend_View_Interface
     */
    public function setView(Zend_View_Interface $view) {
        $this->view = $view;
    }
}
