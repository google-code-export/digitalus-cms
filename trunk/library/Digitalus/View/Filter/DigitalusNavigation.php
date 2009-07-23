<?php
class Zend_View_Filter_DigitalusNavigation extends Digitalus_Content_Filter 
{
    public $tag = 'digitalusNavigation';   

    protected function _callback($matches)
    {
        $attr = $this->getAttributes($matches[0]);
        if (is_array($attr)) {
            $id = $attr['id'];
            $parentId = isset($attr['parent_id']) ? $attr['parent_id'] : 0;
            $levels = isset($attr['levels']) ? $attr['levels'] : 1;
            $separator = isset($attr['separator']) ? $attr['separator'] : null;
            $root = isset($attr['root']) ? $attr['root'] : null;
            switch ($attr['type']) {
                case 'menu':
                    return $this->view->renderMenu($parentId, $levels, null, $id);
                    break;
                case 'submenu':
                    return $this->view->renderSubmenu($levels, $id);
                    break;
                case 'breadcrumbs':
                    return $this->view->renderBreadcrumbs($separator, $root);
                    break;
            }
        }
        return null;
    }
}
?>