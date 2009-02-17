<?php
class DSF_View_Helper_Filesystem_RenderFileBrowser
{
    public function RenderFileBrowser($parentId, $link, $basePath = null, $level = 0, $id = 'fileTree')
    {
        $links = array();
        $tree = new Page();

        $children = $tree->getChildren($parentId, null, 'name');

        foreach ($children as $child) {
            if ($tree->hasChildren($child)) {
                $newLevel = $level + 1;
                $submenu = $this->view->RenderFileBrowser($child->id, $link, $basePath, $newLevel);
                $icon = 'folder.png';
            } else {
                $icon = 'page_white_text.png';
                $submenu = false;
            }

            if (isset($child->label) && !empty($child->label)) {
                $label = $child->label;
            } else {
                $label = $child->name;
            }
            $links[] = '<li class="menuItem">' . $this->view->link($label, $link . $child->id, $icon) . $submenu . '</li>';
        }

        if (is_array($links)) {
            if ($level == 0) {
                $strId = "id='{$id}'";
            } else {
                $strId = null;
            }
            $filetree = "<ul {$strId}>" . implode(null, $links) . '</ul>';
            return  $filetree;
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