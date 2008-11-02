<?php
class Zend_View_Helper_RenderModuleForm
{

	/**
	 * comments
	 */
	public function RenderModuleForm($module, $action, $parameters){
	    $dir = './application/modules/' . $module . '/views/scripts';
	    $helpers = './application/modules/' . $module . '/views/helpers';
		$path = "/public/" . $action . ".form.phtml";
		$fullPath = $dir . $path;
	    if(file_exists($fullPath))
	    {
    	    $this->view->addScriptPath($dir);
    	    $this->view->addHelperPath($helpers);
    		$this->view->formParams = $parameters;
		      return $this->view->render($path);
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
