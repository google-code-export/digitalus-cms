<?php
class DSF_View_Helper_Navigation_RenderSubmenuAsIntro
{	
	public function RenderSubmenuAsIntro($id = 'subnav')
	{
		$parents = $this->view->pageObj->getParents();
		if(is_array($parents) && count($parents) > 0)
		{
			$parent = $parents[0];
			$subMenu = $parent;
		}
		
		if($subMenu < 1)
		{
			$subMenu = $this->view->page;
		}
		
		$page = new ContentPage();
		$children = $this->view->pageObj->getChildren($subMenu->id);
		$basePath = '/' . DSF_Toolbox_String::addHyphens($subMenu->label);
				
		foreach ($children as $child)
		{
			
			$link = $basePath . '/' . DSF_Toolbox_String::addHyphens($child->title);
			$linkId = DSF_Toolbox_String::addUnderscores($page->path, true);
			$subPages[] ="<h3><a href='{$link}' class='{$class}' id='page-{$child->id}'>{$child->title}</a></h3><p>" . $this->view->TruncateText($child->content, 15) . '</p>';
		}
		
		if($subPages)
		{
			return "<div id='{$id}'>" . implode(null, $subPages) . "</div>";
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