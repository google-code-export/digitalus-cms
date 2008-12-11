<?php
/**
 *
 * @author forrest lyman
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * RenderContentTemplate helper
 *
 * @uses viewHelper DSF_View_Helper_Content
 */
class DSF_View_Helper_Content_RenderContentTemplate {
    
    /**
     * @var Zend_View_Interface 
     */
    public $view;
    
    /**
     *  
     */
    public function renderContentTemplate($template, $content) {
        $loader = new DSF_Content_Template_Loader();
        $template = $loader->load($template);
        return $template->render($content);
    }
    
    /**
     * Sets the view field 
     * @param $view Zend_View_Interface
     */
    public function setView(Zend_View_Interface $view) {
        $this->view = $view;
    }
}
