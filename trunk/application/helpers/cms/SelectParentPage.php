<?php
class Zend_View_Helper_SelectParentPage
{	
	public function SelectParentPage($name, $value=null, $attribs = null)
	{
        $mdlIndex = new Page();
        $index = $mdlIndex->getIndex();

        //add the site root
        if(is_array($index)) {
        	array_unshift($index, 'Site Root');        	
        }else{
        	$index[0] = 'Site Root';        	
        }
		return $this->view->formSelect($name, $value, $attribs, $index);
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