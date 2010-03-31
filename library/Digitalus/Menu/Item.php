<?php

class Digitalus_Menu_Item
{
    public $view;
    protected $_item;
    public $id;
    public $active     = false;
    public $visible    = false;
    public $hasSubmenu = false;

    public $page;

    /**
     * Constructor
     *
     * @param   object  $item   A Zend_Db_Table_Row object
     */
    public function __construct(Zend_Db_Table_Row $item)
    {
        $this->setView();
        $this->_item = $item;
        $this->id    = $this->_item->id;
        $this->page = Zend_Navigation_Page::factory($this->_getPageAsArray());
        $this->page->pages = $this->_getChildrenAsArray();
    }

    public function isActive($item = null) {
        if (empty($item)) {
            $item = $this->_item;
        }
        $this->active = false;
        $uri = new Digitalus_Uri();
        $uriString = $uri->toString();
        if ('/' . Digitalus_Toolbox_Page::getUrl($item) == $uriString ||
            ($item->is_home_page == 1 && empty($uriString))) {
            $this->active = true;
        }
        return $this->active;
    }

    public function isVisible($item = null) {
        if (empty($item)) {
            $item = $this->_item;
        }
        $this->visible = false;
        if (1 == $item->publish_level && $item->show_on_menu) {
            $this->visible = true;
        }
        return $this->visible;
    }


    /**
     * Check whether current item has children
     *
     * @return  bool  Returns true if the current item has children, otherwise false
     */
    protected function _hasChildren()
    {
        $mdlMenu = new Model_Menu();
        if ($mdlMenu->hasChildren($this->id)) {
            return true;
        }
        return false;
    }

    /**
     * Get Page data as array
     *
     * @return  array  Returns an array of the page data, otherwise an empty array
     */
    protected function _getPageAsArray($item = null)
    {
        if (empty($item)) {
            $item = $this->_item;
        }
        $this->page = array(
            'active'    => $this->isActive($item),
            'id'        => $item->id,
            'label'     => Digitalus_Toolbox_Page::getLabel($item),
            'title'     => Digitalus_Toolbox_Page::getLabel($item),
            'uri'       => Digitalus_Toolbox_Page::getUrl($item),
            'visible'   => $this->isVisible($item),
            'class'     => 'menuItem',
        );
        return $this->page;
    }

    /**
     * Add children if they exist
     *
     * @return  array  Returns an array of all children, otherwise an empty array
     */
    protected function _getChildrenAsArray()
    {
        $subPages = array();
        if ($this->_hasChildren()) {
            $mdlMenu = new Model_Menu();
            $children = $mdlMenu->getChildren($this->id);
            foreach ($children as $child) {
                $page = new Digitalus_Menu_Item($child);
                $subPages[] = $page->_getPageAsArray();
            }
        }
        return $subPages;
    }

    /**
     * Check whether navigation item is the currently selected
     *
     * @param   string  $ignoreParents  Ignore the parent navigation items when setting CSS class or id
     * @return  bool                    Returns true when the item is the currently selected, otherwise false
     */
    public function isSelected($ignoreParents = false)
    {
        $currentPage = Digitalus_Builder::getPage();
        $currentPageId = $currentPage->getId();
        if ($this->id == $currentPageId) {
            return true;
        } else if ($ignoreParents == false) {
            $page = new Model_Page();
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
     * @return  Digitalus_Menu    Returns a Digitalus_Menu object of this item
     */
    public function getSubmenu()
    {
        return new Digitalus_Menu($this->id);
    }

    /**
     * Retrieve the inner page of this item
     *
     * @return  stdClass|null   Returns null or a stdClass object
     */
    public function getInnerPage()
    {
        $page = new Model_Page();
        return $page->open($this->id);
    }

    /**
     * Retrieve the inner item
     *
     * @return  Zend_Db_Table_Row   Returns a Zend_Db_Table_Row object
     */
    public function getItem()
    {
        return $this->_item;
    }

    /**
     * Return the current menu item as hyperlink
     *
     * @param     string  $id            The CSS id for the <a> tag
     * @param     string  $class         The CSS class for the <a> tag
     * @param     string  $currentId     The CSS id for the <a> tag of the current link
     * @param     string  $currentClass  The CSS class for the <a> tag of the current link
     * @param     string  $ignoreParents Ignore the parent navigation items when setting CSS class or id
     * @param     boolean $translate     Define whether labels should be translated
     * @return    string                 The string of the hyperlink <a> tag
     */
    public function asHyperlink($id = null, $class = null, $currentId = null, $currentClass = null, $ignoreParents = false, $translate = false)
    {
        if (isset($id) && !empty($id)) {
            $id = ' id="' . $id . '"';
        }
        if (isset($class) && !empty($class)) {
            $class = ' class="' . $class . '"';
        }
#        $cleanLink = Digitalus_Toolbox_String::addHyphens($this->link);
        $cleanLink = str_replace(' ', '_', trim($this->link));


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
        if (true == $translate) {
            $this->setView();
            $label = $this->view->getTranslation($this->label);
        } else {
            $label = $this->label;
        }
        return '<a href="' . $baseUrl . '/' . $cleanLink . '"' . $id . $class . '>' . $label . '</a>' . PHP_EOL;
    }

    public function getView()
    {
        return $this->view;
    }

    public function setView(Zend_View $view = null)
    {
        if ($view == null) {
            $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
            if (null === $viewRenderer->view) {
                $viewRenderer->initView();
            }
            $this->view = $viewRenderer->view;
        } else {
            $this->view = $view;
        }
    }
}