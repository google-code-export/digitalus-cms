<?php
class Zend_View_Helper_SelectAdminRole
{	
	public function SelectAdminRole($name, $value, $attribs = false)
	{
		$data['admin'] = "Site Administrator";
		$data['superadmin'] = "Super Administrator";
		return $this->view->formSelect($name, $value, $attribs, $data);
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