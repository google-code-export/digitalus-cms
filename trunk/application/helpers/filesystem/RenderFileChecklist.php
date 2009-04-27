<?php
class DSF_View_Helper_Filesystem_RenderFileChecklist
{
    public function RenderFileChecklist($values = array(), $parentId, $level = 0, $id = 'fileChecklist')
    {
            $links = array();
            $page = new Page();

            $children = $page->getChildren($parentId);

            foreach ($children as $child) {
                if ($page->hasChildren($child)) {
                    $newLevel = $level + 1;
                    $submenu = $this->view->RenderFileChecklist($values, $child->id, $newLevel);
                } else {
                    $submenu = false;
                }

                if (in_array($child->id, $values)) {
                    $checked = 1;
                } else {
                    $checked = 0;
                }

                $checkbox = $this->view->formCheckbox('file_' . $child->id, $checked);

                $links[] ='<li class="page">' . $checkbox . $child->name . $submenu . '</li>';
            }

            if (is_array($links)) {
                if ($level == 0) {
                    $strId = "id='{$id}'";
                } else {
                    $strId = null;
                }
                $fileChecklist = "<ul {$strId}>" . implode(null, $links) . '</ul>';
                return  $fileChecklist;
            }
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