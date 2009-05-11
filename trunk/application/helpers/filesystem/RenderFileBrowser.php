<?php
class DSF_View_Helper_Filesystem_RenderFileBrowser
{
    public function RenderFileBrowser($parentId, $link, $basePath = null, $level = 0, $id = 'fileTree', $withRoot = false)
    {
        $links = array();
        $tree = new Model_Page();

        $children = $tree->getChildren($parentId, null, 'name');

        // add a link for site root
        if (isset($withRoot) && $withRoot == true) {
            $links[] = $this->_getSiteRootElement();
        }

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
     * Get Site Root element
     *
     * @return string
     */
    protected function _getSiteRootElement()
    {
        $request = $this->view->getRequest();
        $pageId  = $request->getParam('id', 0);

        $siteRoot = '<li class="menuItem" style="background-image: none; padding: 0px;">'
                  . '<a class="link" href="/admin/page/move/id/' . $pageId . '/parent/0">'
                  . '<img class="icon" alt="' . $this->view->getTranslation('Site Root') . '" src="/images/icons/folder.png"/>'
                  . $this->view->getTranslation('Site Root')
                  . '</a>'
                  . '</li>';

        return $siteRoot;
    }

    /**
     * Set this->view object
     *
     * @param  Zend_View_Interface $view
     * @return Zend_View_Helper_DeclareVars
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }

}