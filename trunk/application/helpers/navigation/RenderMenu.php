<?php
class DSF_View_Helper_Navigation_RenderMenu
{	
	public function RenderMenu($parentId, $depth = 1 , $level = 0, $basePath = null, $id = 'nav')
	{
		if($this->view->pageObj->isOnline())
		{
		    $links = array();	
			if($level <= $depth - 1)
			{
				$links = array();
				$page = new Content();
		
				$children = $page->getChildren($parentId, true);
			    $numberOfChildren = count($children);
				$counter = 0;
				foreach ($children as $child)
				{
	                $counter++;
					if(!empty($child->label))
					{
						$label = $child->label;
					}else{
						$label = $child->title;
					}
					
					$link = strtolower($basePath . '/' . DSF_Toolbox_String::addHyphens($child->title));
					
					//decide if the item is selected
					//note that we add the selected class to each item
					// uri: home/about
					// home and about would both be selected
					$u = new DSF_Uri();
					$uri = $u->toArray();
					
					$arrlink = $u->toArray($link);
					
					$count = count($arrlink);
					for($i = 0; $i <= $count - 1; $i++)
					{
						if(isset($uri[$i]) && $uri[$i] == $arrlink[$i])
						{
							$selected = true;
						}else{
							$selected = false;
						}
					}
					
					if($selected)
					{
						$class[] = 'selected';
					}
					
					if($counter == 1){
					    $class[] = 'first';
					}elseif ($counter == $numberOfChildren){
					    $class[] = 'last';
					}
					
					$children = $page->getChildren($child->id);
					if($children->count() > 0)
					{
						$class[] = 'dir';
						$newLevel = $level + 1;
						$submenu = $this->view->renderMenu($child->id, $depth, $newLevel, $link);
					}else{
						$class[] = 'page';
						$submenu = false;
					}
					
					$class = implode(' ', $class);
					
					$links[] ="<li class='menuItem'><a href='{$link}' class='{$class}' id='page-{$child->id}'>{$label}</a>" . $submenu . '</li>';
					$selected = false;
					unset($class);
				}
			}
			
			if(is_array($links) && count($links) > 0)
			{
				if($level == 0){
					$strId = "id='{$id}'";
				}else{
				    $strId = null;
				}
				return  "<ul {$strId}>" . implode(null, $links) . "</ul>";
			}
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