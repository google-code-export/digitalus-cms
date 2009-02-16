<?php

class DSF_Menu_Item {
    protected $_innerItem;
    public $id;
    public $label;
    public $link;
    public $visible;
    public $hasSubmenu = false;

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

    public function isSelected()
    {
        $currentPage = DSF_Builder::getPage();
        $currentPageId = $currentPage->getId();
        if ($this->id == $currentPageId) {
            return true;
        } else {
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

    public function getSubmenu()
    {
        return new DSF_Menu($this->_innerItem->id);
    }

    public function getInnerPage()
    {
        $page = new Page();
        return $page->open($this->id);
    }

    public function getInnerItem()
    {
        return $this->_innerItem;
    }

    public function asHyperlink($id = null, $class = null)
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
        return "<a href='" . $baseUrl . "/{$cleanLink}' {$id} {$class}>$this->label</a>";
    }
}

?>