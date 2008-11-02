<?php

class  DSF_View_Helper_Admin_UsersModuleLinks
{

	/**
	 * comments
	 */
	public function UsersModuleLinks($id = 'moduleList'){
		$u = new User();
		$modules = $u->getCurrentUsersModules();
			if($modules) {
			foreach ($modules as $module) {
					$moduleLinks[] = "<a href='/mod_{$module}/index' class='{$module}'>{$module}</a>";	
			}
		}
		if(is_array($moduleLinks))
		{
			return $this->view->HtmlList($moduleLinks, null, array('id' => $id), false);
		}
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
			