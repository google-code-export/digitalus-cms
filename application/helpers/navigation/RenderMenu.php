<?php
class DSF_View_Helper_Navigation_RenderMenu
{
    public $partialScript = 'partials/navigation/menu-item.phtml';
    public $levels = 1;
    public $currentLevel = 1;

    public function RenderMenu($parentId = 0, $levels = null, $currentLevel = null, $id = 'menu', $partialScript = null)
    {
        if (null === $partialScript) {
            $partialScript = $this->partialScript;
        }

        if (null !== $levels) {
            $this->levels = $levels;
        }

        if (null !== $currentLevel) {
            $this->currentLevel = $currentLevel;
        }

        $menu = new DSF_Menu($parentId);
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

                $links[] = $this->view->partial($partialScript, $data);
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
     * @param  Zend_this->view_Interface $this->view
     * @return Zend_this->view_Helper_DeclareVars
     */
    public function setview(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }
}