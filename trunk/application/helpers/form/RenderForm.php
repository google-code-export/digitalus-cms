<?php
class DSF_View_Helper_Form_RenderForm
{

	/**
	 * comments
	 */
	public function RenderForm($action, $rows = array(), $submitText = 'Save Changes', $multipart = false){
		if($multipart)
		{
			$encType = "enctype='multipart/form-data'";
		}
		$xhtml = "<form action='{$action}' method='post' {$encType} >";
		$xhtml .= implode(null, $rows);
		$xhtml .= $this->view->formSubmit(str_replace(' ', '_', $submitText), $submitText);
		$xhtml .= "</form>";
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