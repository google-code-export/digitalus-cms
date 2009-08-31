<?php

class Digitalus_Menu {
    public $items = null;
    protected  $_parentId;

    /**
     * this function sets up then loads the menu
     *
     * @todo this needs to be cached
     *
     * @param int $parentId
     * @param int $levels
     */
    public function __construct($parentId = 0) {
        $this->_parentId = $parentId;
        $this->_load();
    }

    /**
     * this function loads the current menu and is run automatically by the constructor
     *
     */
    protected function _load()
    {
        $page = new Model_Page();
        $children = $page->getChildren($this->_parentId);
        if ($children != null && $children->count() > 0) {
            foreach ($children as $child) {
                if ($child->show_on_menu == 1 && $child->publish_level == 1) {
                    $this->items[] = new Digitalus_Menu_Item($child);
                }
            }
        }
    }
}
?>