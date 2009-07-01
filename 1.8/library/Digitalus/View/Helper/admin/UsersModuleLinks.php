<?php

class  Digitalus_View_Helper_Admin_UsersModuleLinks
{

    /**
     * comments
     */
    public function UsersModuleLinks($id = 'moduleList'){
        $u = new Model_User();
        $modules = $u->getCurrentUsersModules();
        if ($modules) {
            foreach ($modules as $module) {
                    $moduleLinks[] = "<a href='/mod_{$module}/index' class='{$module}'>{$module}</a>";
            }
        }
        if (is_array($moduleLinks)) {
            return $this->view->HtmlList($moduleLinks, null, array('id' => $id), false);
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