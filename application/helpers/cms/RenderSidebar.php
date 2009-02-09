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
     * @param  Zend_this->view_Interface $this->view
     * @return Zend_this->view_Helper_DeclareVars
     */
    public function setview(Zend_view_Interface $view)
    {
        $this->view = $view;
        return $this;
    }
}