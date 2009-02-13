<?php
class  DSF_View_Helper_Admin_IsAllowed
{

    /**
     * comments
     */
    public function IsAllowed($output, $module, $controller = null, $action = null)
    {
        $role = 'admin';
        $acl = Zend_Registry::get('acl');
        //go from more specific to less specific
        $moduleLevel = $module;
        $controllerLevel = $moduleLevel . '_' . $controller;
        $actionLevel = $controllerLevel . '_' . $action;

        if (null != $action && $acl->has($actionLevel)) {
            $resource = $actionLevel;
        } elseif (null != $controller && $acl->has($controllerLevel)) {
            $resource = $controllerLevel;
        } else {
            $resource = $moduleLevel;
        }

        if ($acl->has($resource)) {
            if ($acl->isAllowed($role, $resource)) {
                return $output;
            }
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
