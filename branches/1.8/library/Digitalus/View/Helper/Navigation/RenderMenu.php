<?php
class Digitalus_View_Helper_Navigation_RenderMenu
{
    public $levels = 1;

    public function RenderMenu($parentId = 0, $levels = 1, $currentLevel = 1, $id = 'menu')
    {
        if(null == $currentLevel) {
            $currentLevel = 1;
        }
        
        $menu = new Digitalus_Menu($parentId);
        $links = array();

        if (count($menu->items) > 0) {
            foreach ($menu->items as $item) {
                $data = new stdClass();
                $data->item = $item;
                $data->menuId = $id;

                //check for a submenu
                if (($levels > $currentLevel) && ($item->hasSubmenu)) {
                    $newLevel = $currentLevel + 1;
                    $data->submenu = $this->view->RenderMenu($item->id, $levels, $newLevel, 'submenu_' . $item->id);
                } else {
                    $data->submenu = null;
                }

                $menuItem = "<li id='{$id}_item_wrapper_{$item->id}' class='menuItem'>";
                $class = $item->isSelected() ? 'selected' : 'unselected';
                
            	if($item->isSelected()){
            	    $class="selected";
            	}else{
            		$class = "unselected";
            	}
            	
            	$menuItemId = $id . "_item_" . $item->id;
                $menuItem .= $item->asHyperlink($menuItemId, $class);
                if($data->submenu != null) {
                    $menuItem .= $data->submenu;
                }
                $menuItem .= "</li>";

                $links[] = $menuItem;
                unset($menuItem);
            }
        }

        if (count($links) > 0) {
            return  "<ul id='{$id}'>" . implode(null, $links) . '</ul>';
        } else {
            return null;
        }
    }

    /**
     * Set this->view object
     *
     * @param  Zend_View_Interface $view
     * @return Zend_View_Helper_DeclareVars
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }
}