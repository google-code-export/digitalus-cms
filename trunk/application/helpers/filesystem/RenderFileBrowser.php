<?php
class DSF_View_Helper_Filesystem_RenderFileBrowser
{	
	public function RenderFileBrowser($parentId, $basePath = null, $level = 0, $id = 'fileTree')
	{
		$cache = Zend_Registry::get('cache');
		if($filetree = $cache->load('filetree')) {
			return $filetree;
		}else{
			$links = array();
			$tree = new Page();
	
			$children = $tree->getChildren($parentId);
			
			foreach ($children as $child)
			{			
				if($tree->hasChildren($child))
				{
					$newLevel = $level + 1;
					$submenu = $this->view->RenderFileBrowser($child->id, $basePath, $newLevel);
					$icon = 'folder.png';
				}else{
				    $icon = "page_white_text.png";
					$submenu = false;
				}
				
				$links[] ="<li class='menuItem'>" . $this->view->link($child->name, '/admin/page/edit/id/' . $child->id, $icon) . $submenu . '</li>';
			}
			
			if(is_array($links))
			{
				if($level == 0){
					$strId = "id='{$id}'";
				}else{
				    $strId = null;
				}
				$filetree = "<ul {$strId}>" . implode(null, $links) . "</ul>";
				$cache->save($filetree, "filetree", array('tree'));
				return  $filetree;
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