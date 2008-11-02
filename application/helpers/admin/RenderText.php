<?php
class DSF_View_Helper_Admin_RenderText
{
  
    /**
     *
     * @return unknown
     */
	public function RenderText($key, $tag = null)
	{
	    $xhtml = null;
	    if($tag != null){
	        $xhtml .= "<{$tag}>";
	    }
	    $xhtml .= $this->view->GetTranslation($key);
	    if($tag != null){
	        $xhtml .= "</{$tag}>";
	    }
	    return $xhtml;
	    
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
