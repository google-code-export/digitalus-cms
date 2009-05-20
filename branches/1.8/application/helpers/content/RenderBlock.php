<?php
class Digitalus_View_Helper_Content_RenderBlock
{
    public function RenderBlock ($path)
    {
        $mdlPage = new Model_Page();
        $uriObj = new Digitalus_Uri($path);
        $pointer = $mdlPage->fetchPointer($uriObj->toArray());
        $pageObj = $mdlPage->open($pointer, $mdlPage->getDefaultVersion());
        $namespace = $pageObj->page->namespace . '_' . $pointer;
        return $this->view->RenderContentTemplate($pageObj->page->content_template, $pageObj->content, $namespace);
    }
    /**
     * Sets the view field
     * @param $view Zend_View_Interface
     */
    public function setView (Zend_View_Interface $view)
    {
        $this->view = $view;
    }
}