<?php
class Zend_View_Helper_LoadModule
{
    /**
     * render a module page like news_showNewPosts
     */
    public function LoadModule($module, $action, $params = null)
    {
        //validate the module
        $modules = DSF_Filesystem_Dir::getDirectories('./application/modules');

        // @todo: validate the action as well
        if (in_array($module, $modules)) {
            if (is_array($params)) {
                foreach ($params as $k => $v) {
                    $paramsArray[(string)$k] = (string)$v;
                }
            }
            return $this->view->action($action, 'public', 'mod_' . $module, $paramsArray);
        }
    }

    /**
     * Set this->view object
     *
     * @param  Zend_this->view_Interface $this->view
     * @return Zend_this->view_Helper_DeclareVars
     */
    public function setview(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }
}