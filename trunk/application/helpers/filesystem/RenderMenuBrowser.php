<?php
class DSF_View_Helper_Filesystem_RenderMenuBrowser
{
    public function RenderMenuBrowser($parentId, $basePath = null, $id = 'menuTree')
    {
        $menu = new Menu();

        $children = $menu->getMenuItems($parentId, true);

        foreach ($children as $child) {
            $label = $child->title;

            if (!empty($child->label)) {
                $label =  $child->label . ' / ' . $label;
            }

            $class = 'menu';
            $submenu = $this->view->RenderMenuBrowser($child->id, $link);

            $linkId = DSF_Toolbox_String::addUnderscores($menu->path, true);
            $links[] ="<li class='menuItem'><a href='/admin/navigation/open/id/{$child->id}' class='{$class}' id='page-{$child->id}'>{$label}</a>" . $submenu . '</li>';
        }

        if (is_array($links)) {
            if ($level == 0) {
                $strId = "id='{$id}'";
            }
            return  "<ul {$strId}>" . implode(null, $links) . '</ul>';
        }
    }

    /**
     * Set this->view object
     *
     * @param  Zend_this->view_Interface $this->view
     * @return Zend_this->view_Helper_DeclareVars
     */
    public function setview(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }
}