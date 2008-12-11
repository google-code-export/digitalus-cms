<?php

class DSF_Menu_Item {
    protected $_innerItem;
    public $id;
    public $label;
    public $link;
    public $visible;
    public $hasSubmenu = false;
    
    function __construct(Zend_Db_Table_Row $item) {
        $this->_innerItem = $item;
        $this->label = DSF_Toolbox_Page::getLabel($item);
        $this->link = DSF_Toolbox_Page::getUrl($item);
        
        if($item->show_on_menu) {
            $this->visible = true;
        }else{
            $this->visible = false;
        }
        
        $page = new Page();
        if($page->hasChildren($item)) {
            $this->hasSubmenu = true;
        }else{
            $this->hasSubmenu = false;
        }
    }
    
    public function getSubmenu()
    {
        return new DSF_Menu($this->_innerItem->id);
    }
    
    public function asHyperlink($id = null, $class = null)
    {
        $cleanLink = DSF_Toolbox_String::addHyphens($this->link);
        $front = Zend_Controller_Front::getInstance();
        $baseUrl = $front->getBaseUrl();
        return "<a href='" . $baseUrl . "/{$cleanLink}' id='{$id}' class='{$class}'>$this->label</a>";
    }
}

?>