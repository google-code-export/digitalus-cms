<?php 
class Menu extends Page
{
	public function open($menuId = 0)
	{
		$page = new Page();
		$menu = array();
		$children = $page->getChildren($menuId);
		if($children->count() > 0) {
		    foreach($children as $child) {
		        $value = $this->getUrl($child);
		        $key = $this->getLabel($child);
		        $menu[$key] = $value;
		    }
		}
	    return $menu;
	}
	
	public function hasMenu($menuId)
	{
		if($this->hasChildren($menuId)){
			return true;
		}
	}
	
	public function getLabel($page)
	{
	    if(!empty($page->label)) {
	        return $page->label;
	    }else{
	        return $page->name;
	    }
	}
	
	public function getUrl($page)
	{
	    return '#';
	}
}