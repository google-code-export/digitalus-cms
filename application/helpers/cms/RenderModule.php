<?php
class Zend_View_Helper_RenderModule
{
	/**
	 * render a module page like news_showNewPosts
	 */
	public function RenderModule(){
		$modulePage = $this->view->pageObj->getModule();
		if($modulePage){
    		$module = $modulePage->module;
    		$action = $modulePage->action;
    		
    		$params = array();
    		
    		$moduleParams = $modulePage->params;
    		if(is_array($moduleParams))
    		{
    		    $params = array_merge($params, $moduleParams);
    		}
    		$pageParams = $this->view->pageObj->getParams();
    		if(is_array($pageParams))
    		{
    		     $params = array_merge($params, $pageParams);
    		}
    		
    		return $this->view->LoadModule($module, $action, $params);
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