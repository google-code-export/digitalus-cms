<?php
class Zend_View_Helper_RenderSidebar
{

    /**
     * comments
     */
    public function RenderSidebar(){
        $path = str_replace('.phtml', '.sidebar.phtml', $this->view->actionScript);

        return $this->view->render($path);
    }

    /**
     * Set this->view object
     *
     * @param  Zend_View_Interface $view
     * @return Zend_View_Helper_DeclareVars
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }

}