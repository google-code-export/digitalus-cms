<?php
class Model_Menu extends Model_Page
{
    public function getMenus()
    {
        //todo: figure out how to do this with a pure select object
        $sql = 'SELECT DISTINCT parent_id FROM ' . Digitalus_Db_Table::getTableName('pages');
        $result = $this->_db->fetchAll($sql);
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
        if ($children->count() > 0) {
            if ($asRowset == true) {
                return $children;
            } else {
                foreach ($children as $child) {
                    $value = $this->getUrl($child);
                    $key = $this->getLabel($child);
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
        if (!empty($page->label)) {
            return $page->label;
        } else {
            return $page->name;
        }
    }

    public function getUrl($page)
    {
        return '#';
    }

    public function updateMenuItems($ids, $labels, $visibility) {
        if (is_array($ids)) {
            for ($i = 0; $i <= (count($ids) - 1); $i++) {
                $this->updateMenuItem($ids[$i], $labels[$i], $visibility[$i], $i);
            }
        }
    }

    public function updateMenuItem($id, $label, $visibility, $position)
    {
        $page = $this->find($id)->current();
        if ($page) {
            $page->label = $label;
            $page->show_on_menu = $visibility;
            $page->position = $position;
            return $page->save();
        }
        return false;
    }
}