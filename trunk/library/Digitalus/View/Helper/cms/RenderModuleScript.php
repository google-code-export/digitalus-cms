<?php
/**
 * this helper script renders one of the public cms module pages
 * these are mapped to
 * /application/modules/{module name}/views/scripts/public/{script name}.phtml
 * you can optionally send this helper an array of parameters
 * these will be added to the view object and can be retrieved later by going like:
 * $param = $this->view->{module name}->{param}
 *
 */
class Digitalus_View_Helper_Cms_RenderModuleScript
{
    public function RenderModuleScript($module, $script, $params = false)
    {
        if(is_array($params)) {
            $this->view->$module = new stdClass();
            $mdl = $this->view->$module;
            foreach ($params as $k => $v) {
                $mdl->$k = $v;
            }
        }
        $this->view->addScriptPath('./application/modules/' . $module . '/views/scripts/public');
        return $this->view->render($script . '.phtml');
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