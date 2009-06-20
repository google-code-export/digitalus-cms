<?php
class Digitalus_View_Helper_Navigation_RenderBreadcrumbs
{
    public function RenderBreadcrumbs($separator = ' > ', $siteRoot = 'Home')
    {
        $parents = $this->view->pageObj->getParents();
        if (is_array($parents) && count($parents) > 0) {
            $path = null;
            foreach ($parents as $parent) {
                $label = $this->view->pageObj->getLabel($parent);
                $link = '/' . Digitalus_Toolbox_String::addHyphens($label);
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
     * @param  Zend_View_Interface $view
     * @return Zend_View_Helper_DeclareVars
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }
}