<?php
class DSF_View_Helper_General_CleanUri
{
	/**
	 * removes any params from the uri
	 */
	public function CleanUri($absolute = false){
	    $uri = $this->view->pageObj->getCleanUri();
	    if($absolute && !empty($uri)){
	        $uri = '/' . $uri;
	    }
        return  DSF_Toolbox_String::addHyphens($uri);
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