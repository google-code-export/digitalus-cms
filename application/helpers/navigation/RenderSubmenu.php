<?php
class DSF_View_Helper_Navigation_RenderSubMenu
{
    public function RenderSubMenu($levels = 2, $id = 'subnav')
    {
    	$page = DSF_Builder::getPage();
        $parents = $page->getParents();
        if (is_array($parents) && count($parents) > 0) {
	        // parents is returned as an ascending array, we need it to descend
	        $parents = array_reverse($parents);
            $rootParent = array_shift($parents);
            $rootParentId = $rootParent->id;
        }else{
        	//this page is a root level page.  
        	$rootParentId = $page->getId();
        }

        if ($rootParentId > 0) {
            return $this->view->RenderMenu($rootParentId, $levels, null, $id);
        }
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