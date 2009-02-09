<?php
class DSF_View_Helper_Navigation_RenderBreadcrumbs
{
    public function RenderBreadcrumbs($separator = ' > ', $siteRoot = 'Home')
    {
        $parents = $this->view->pageObj->getParents();
        if (is_array($parents) && count($parents) > 0) {
            $path = null;
            foreach ($parents as $parent) {
                $label = $this->view->pageObj->getLabel($parent);
                $link = '/' . DSF_Toolbox_String::addHyphens($label);
                $path .= $link;
                $arrLinks[] = "<a href='{$path}' class='breadcrumb'>{$parent->title}</a>";
            }
        }
        $arrLinks[] = "<a href='' class='breadcrumb last'>{$this->view->page->title}</a>";

        return implode($separator, $arrLinks);
    }

    /**
     * Set this->view object
     *
     * @param  Zend_this->view_Interface $this->view
     * @return Zend_this->view_Helper_DeclareVars
     */
    public function setview(Zend_view_Interface $view)
    {
        $this->view = $view;
        return $this;
    }
}