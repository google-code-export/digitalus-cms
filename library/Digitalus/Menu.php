<?php
class Digitalus_Menu
{
    public    $pages = array();
    protected $_parentId;

    /**
     * this function sets up then loads the menu
     *
     * @todo this needs to be cached
     *
     * @param int $parentId
     * @param int $levels
     */
    public function __construct($parentId = 0)
    {
        $this->_parentId = $parentId;
        $this->_load();
    }

    /**
     * this function loads the current menu and is run automatically by the constructor
     *
     */
    protected function _load()
    {
        $mdlMenu = new Model_Menu();
        $children = $mdlMenu->getChildren($this->_parentId);
        if ($children != null && $children->count() > 0) {
            foreach ($children as $child) {
                $item = new Digitalus_Menu_Item($child);
                if ($item->isVisible()) {
                    $this->pages[] = $item->page;
                }
            }
            $container = new Zend_Navigation($this->pages);
            Zend_Registry::set('Zend_Navigation', $container);
        }
    }
}