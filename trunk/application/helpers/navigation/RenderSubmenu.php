<?php
class DSF_View_Helper_Navigation_RenderSubMenu
{	
	public function RenderSubMenu($levels = 2, $id = 'subnav')
	{
		$parents = $this->view->page->getParents();
		if(is_array($parents) && count($parents) > 0)
		{
			$parent = $parents[0];
			$subMenu = $parent;
		}
		
		if(!isset($subMenu))
		{
			$subMenu = $this->view->page;
		}
		
		if(!empty($subMenu->label))
		{
			$label = $subMenu->label;
		}else{
			$label = $subMenu->title;
		}
		
		if($subMenu->id > 0){
	    		return $this->view->RenderMenu($subMenu->id, $levels, 0, '/' . DSF_Toolbox_String::addHyphens($label), $id);
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