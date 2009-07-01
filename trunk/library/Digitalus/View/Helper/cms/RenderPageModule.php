<?php
class Digitalus_View_Helper_Cms_RenderPageModule
{
    public function RenderPageModule()
    {
        $module = $this->view->pageData->module_page;
        $parts = explode('/', $module);
        if (count($parts) == 2) {
            return $this->view->RenderModuleScript($parts[0], $parts[1]);
        }
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