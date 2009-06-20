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
 * @uses viewHelper Digitalus_View_Helper_Content
 */
class Digitalus_View_Helper_Content_RenderContentTemplate {

    /**
     * @var Zend_View_Interface
     */
    public $view;

    /**
     *
     */
    public function renderContentTemplate($template, $content)
    {
        $template = new Digitalus_Content_Template($template);
        return $template->render($content);
    }

    /**
     * Sets the view field
     * @param $view Zend_View_Interface
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }
}
