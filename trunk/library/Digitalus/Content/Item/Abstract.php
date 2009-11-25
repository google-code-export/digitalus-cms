<?php
abstract class Digitalus_Content_Item_Abstract extends Digitalus_Abstract
{
    /**
     * all of the content types have the following properties as they are part of the stock page table
     *
     */
    public $id;
    public $author_id;
    public $create_date;
    public $publish_date;
    public $archive_date;
    public $publish_level;
    public $name = NULL;
    public $label;
    public $namespace;
    public $content_template;
    public $related_pages;
    public $parent_id;
    public $position;
    public $is_home_page;
    public $show_on_menu;
    public $design;

    private $_pageModel = null;
    protected $_validateNamespace = true;
    const NO_SETTER = 'The setter method for this property does not exist';

    public function __construct($page = null)
    {
        $this->_pageModel = new Model_Page();
        if (null != $page) {
            if (is_object($page)) {
                $this->castPageObject($page);
            } else {
                $this->loadPageObject(intval($page));
            }
        }
    }

    public function castPageObject($pageObject)
    {
        $this->_populate($pageObject);
    }

    public function loadPageObject($id)
    {
        $this->_populate($this->_pageModel->open($id));
    }

    public function save()
    {
        if (isset($this->id)) {
            $this->_update();
        } else {
            $this->_insert();
        }
    }

    protected function _update()
    {
        $data = $this->toArray();
        $data['page_id'] = $this->id;
        unset($data['id']);
        $this->_pageModel->edit($data);
    }

    protected function _insert()
    {
        $this->_pageModel->setNamespace($this->_namespace);
        $page = $this->_pageModel->createPage($this->name);
        $this->id = $page->id;
        $this->_update();
    }

    public function toArray()
    {
        $properties = $this->_getProperties();
        foreach ($properties as $property) {
            if (substr($property, 0, 1) != '_') {
                $array[$property] = $this->$property;
            }
        }
        return $array;
    }

    public function delete()
    {
        if (isset($this->id)) {
            $this->_pageModel->deletePage($this->id);
        } else {
            require_once 'Digitalus/Content/Exception.php';
            throw new Digitalus_Content_Exception($this->view->getTranslation('Unable to delete item - the item is empty!'));
        }
    }

    public function getChildren()
    {
        return $this->_pageModel->getChildren($this->id);
    }

    public function deleteChildren()
    {
        if (isset($this->id)) {
            $this->_pageModel->deleteChildren($this->id);
        } else {
            require_once 'Digitalus/Content/Exception.php';
            throw new Digitalus_Content_Exception($this->view->getTranslation('Unable to delete item - the item is empty!'));
        }
    }

    protected function _populate($data)
    {
        if (null != $data) {
            $properties = $this->_getProperties();
            $page = $data->page;
            $content = $data->content;
            //validate namespace
            if ($page->namespace != $this->_namespace && $this->_validateNamespace == true) {
                require_once 'Digitalus/Content/Exception.php';
                throw new Digitalus_Content_Exception($this->view->getTranslation('Unable to cast type:') . $page->namespace . ' ' . $this->view->getTranslation('to type:') . $this->_namespace);
            }
            foreach ($properties as $property) {
                // try to call the setter method
                $value = $this->_callSetterMethod($property, $data);
                if ($value === self::NO_SETTER) {
                    $value = null;
                    if (isset($page->$property)) {
                        $value = $page->$property;
                    } else if (isset($content[$property])) {
                        $value = $content[$property];
                    }
                }
                $this->$property = $value;
            }
        } else {
            require_once 'Digitalus/Content/Exception.php';
            throw new Digitalus_Content_Exception($this->view->getTranslation('Unable to load content item'));
        }
    }

    protected function _getProperties()
    {
        return array_keys(get_class_vars(get_class($this)));
    }

    protected function _callSetterMethod($property, $data)
    {
        //create the method name
        $property = str_replace('_', ' ', $property);
        $property = ucwords($property);
        $property = str_replace(' ', '', $property);

        $methodName = '_set' . $property;
        if (method_exists($this, $methodName)) {
            return $this->$methodName($data);
        } else {
            return self::NO_SETTER;
        }
    }

}