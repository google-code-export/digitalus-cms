<?php
class Model_Menu extends Model_Page
{
    protected $_menuColumns = array('id', 'parent_id', 'publish_level', 'name', 'position', 'show_on_menu');

    public function getMenus()
    {
        $select = $this->select()
            ->from($this->_name, array('parent_id'))
            ->distinct();
        $result = $this->fetchAll($select);
        if ($result) {
            foreach ($result as $row) {
                $ids[] = $row->parent_id;
            }
            return $this->find($ids);
        }
    }

    public function openMenu($menuId = 0, $asRowset = false)
    {
        $menu = array();
        $children = $this->getChildren($menuId);
        if (isset($children) && $children->count() > 0) {
            if ($asRowset == true) {
                return $children;
            } else {
                foreach ($children as $child) {
                    $value = $this->getUrl($child);
                    $key   = $this->getLabel($child);
                    $menu[$key] = $value;
                }
            }
        }
        return $menu;
    }

    public function hasMenu($menuId)
    {
        if ($this->hasChildren($menuId)) {
            return true;
        }
    }

    public function getLabel($page)
    {
        if (!is_object($page)) {
            $page = $this->find($page)->current();
        }
        return $this->getLabelById($page->id);
    }

    public function getUrl($page)
    {
        return '#';
    }

    public function updateMenuItems($ids, $visibility)
    {
        if (is_array($ids)) {
            for ($i = 0; $i < count($ids); $i++) {
                $this->updateMenuItem($ids[$i], $visibility[$i], $i);
            }
        }
    }

    public function updateMenuItem($id, $visibility, $position)
    {
        $page = $this->find($id)->current();
        if ($page) {
            $page->show_on_menu = $visibility;
            $page->position     = $position;
            return $page->save();
        }
        return false;
    }

    /**
     * this function returns the children of a selected page
     * you can pass it a page id (integer) or a page object
     * you can optionally pass it an array of where clauses
     *
     * @param  mixed  $page
     * @param  array  $where
     * @param  string $order
     * @param  string $limit
     * @param  string $offset
     * @return Zend_Db_Table_Rowset
     */
    public function getChildren($page, $where = array(), $order = null, $limit = null, $offset = null)
    {
        $id = $this->_getPageId($page);

        $orNull = '';
        if (0 == $id) {
            $orNull = ' OR parent_id IS NULL';
        }
        $where = $this->_db->quoteInto('parent_id = ?' . $orNull, $id);

        if (empty($order) || '' == $order) {
            $order = 'position ASC';
        }

        $select = $this->select()
            ->from($this->_name, $this->_menuColumns)
            ->where($where)
            ->order($order)
            ->limit($limit, $offset);

        $result = $this->fetchAll($select);
        if ($result->count() > 0) {
            return $result;
        }
        return null;
    }
}