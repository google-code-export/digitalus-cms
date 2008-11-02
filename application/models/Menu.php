<?php 
class Menu extends Page
{
	public function open($menuId = 0)
	{
		$page = new Page();
		return $page->getChildren($menuId);
	}
	
	public function hasMenu($menuId)
	{
		if($this->hasChildren($menuId)){
			return true;
		}
	}
}