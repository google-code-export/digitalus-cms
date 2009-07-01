<?php

class DSF_Menu_Item {
    protected $_innerItem;
    public $id;
    public $label;
    public $link;
    public $visible;
    public $hasSubmenu = false;

    /**
     * Constructor
     *
     * @param   object  $item   A Zend_Db_Table_Row object
     */
    public function __construct(Zend_Db_Table_Row $item) {
        $this->_innerItem = $item;
        $this->label = DSF_Toolbox_Page::getLabel($item);
        $this->link = DSF_Toolbox_Page::getUrl($item);
        $this->id = $this->_innerItem->id;

        if ($item->show_on_menu) {
            $this->visible = true;
        } else {
            $this->visible = false;
        }

        $page = new Page();
        if ($page->hasChildren($item)) {
            $this->hasSubmenu = true;
        } else {
            $this->hasSubmenu = false;
        }
    }

    /**
     * Check whether navigation item is the currently selected
     *
     * @param   string  $ignoreParents  Ignore the parent navigation items when setting CSS class or id
     * @return  bool                    Returns true when the item is the currently selected, otherwise false
     */
    public function isSelected($ignoreParents = false)
    {
        $currentPage = DSF_Builder::getPage();
        $currentPageId = $currentPage->getId();
        if ($this->id == $currentPageId) {
            return true;
        } elseif ($ignoreParents == false) {
            $page = new Page();
            $parents = $page->getParents($currentPageId);
            if (is_array($parents)) {
                if (isset($parents[$this->id])) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Retrieve this item as Submenu
     *
     * @return  DSF_Menu    Returns a DSF_Menu object of this item
     */
    public function getSubmenu()
    {
        return new DSF_Menu($this->id);
    }

    /**
     * Retrieve the inner page of this item
     *
     * @return  stdClass|null   Returns null or a stdClass object
     */
    public function getInnerPage()
    {
        $page = new Page();
        return $page->open($this->id);
    }

    /**
     * Retrieve the inner item
     *
     * @return  Zend_Db_Table_Row   Returns a Zend_Db_Table_Row object
     */
    public function getInnerItem()
    {
        return $this->_innerItem;
    }

    /**
     * Return the current menu item as hyperlink
     *
     * @param     string  $id            The CSS id for the <a> tag
     * @param     string  $class         The CSS class for the <a> tag
     * @param     string  $currentId     The CSS id for the <a> tag of the current link
     * @param     string  $currentClass  The CSS class for the <a> tag of the current link
     * @param     string  $ignoreParents Ignore the parent navigation items when setting CSS class or id
     * @return    string                 The string of the hyperlink <a> tag
     */
    public function asHyperlink($id = null, $class = null, $currentId = null, $currentClass = null, $ignoreParents = false)
    {
        if (isset($id) && !empty($id)) {
            $id = 'id="' . $id . '"';
        }
        if (isset($class) && !empty($class)) {
            $class = 'class="' . $class . '"';
        }
        $cleanLink = DSF_Toolbox_String::addHyphens($this->link);
        $front = Zend_Controller_Front::getInstance();
        $baseUrl = $front->getBaseUrl();
        if (isset($currentId) && !empty($currentId) && $this->isSelected($ignoreParents)) {
            $id[] = $currentId;
            $id = implode(' ', $id);
        }
        if (isset($currentClass) && !empty($currentClass) && $this->isSelected($ignoreParents)) {
            $class[] = $currentClass;
            $class = implode(' ', $class);
        }
        $id     = (isset($id)    && !empty($id))    ? ' id="'    . $id    . '"' : null;
        $class  = (isset($class) && !empty($class)) ? ' class="' . $class . '"' : null;
        return '<a href="' . $baseUrl . '/' . $cleanLink . '"' . $id . $class . '>' . $this->label . '</a>' . PHP_EOL;
    }
}
?>