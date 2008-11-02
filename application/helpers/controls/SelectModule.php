<?php
class DSF_View_Helper_Controls_SelectModule
{	
	public function SelectModule($name, $value, $attribs = null)
	{
		$modules = DSF_Filesystem_Dir::getDirectories('./application/modules');
		if(is_array($modules))
		{
		    $data[] = "No module selected";
    		foreach ($modules as $module)
    		{
    		    //ignore the template folder
    		    if($module != 'template'){
    		      $data[$module] = $module;
    		    }
    		}
    		return $this->view->formSelect($name, $value, $attribs, $data);
		}else{
		    return "There are no modules currently installed";
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