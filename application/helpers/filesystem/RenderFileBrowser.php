<?php
class DSF_View_Helper_Filesystem_RenderFileBrowser
{	
	public function RenderFileBrowser($parentId, $basePath = null, $id = 'fileTree')
	{
		// @todo: deal with selected menu items
		if($level <= $depth - 1)
		{
			$links = array();
			$menu = new Menu();
	
			$children = $menu->getMenuItems($parentId);
			
			foreach ($children as $child)
			{
				$label = $child->title;

				if(!empty($child->label))
				{
					$label =  $child->label . ' / ' . $label;
				}
				
				$children = $menu->getMenuItems($child->id);
				if($children->count() > 0)
				{
					$class = 'dir';
					$submenu = $this->view->RenderFileBrowser($child->id, $link);
				}else{
					$class = 'page';
					$submenu = false;
				}
				$linkId = DSF_Toolbox_String::addUnderscores($menu->path, true);
				$links[] ="<li class='menuItem'><a href='/admin/page/open/id/{$child->id}' class='{$class}' id='page-{$child->id}'>{$label}</a>" . $submenu . '</li>';
			}
		}
		
		if(is_array($links))
		{
			if($level == 0){
				$strId = "id='{$id}'";
			}
			return  "<ul {$strId}>" . implode(null, $links) . "</ul>";
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