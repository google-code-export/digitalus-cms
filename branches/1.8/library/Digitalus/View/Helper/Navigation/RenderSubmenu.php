<?php
class Digitalus_View_Helper_Navigation_RenderSubMenu extends Zend_View_Helper_Abstract 
{
    public function renderSubMenu($levels = 2, $id = 'subnav')
    {
      $page = Digitalus_Builder::getPage();
        $parents = $page->getParents();
        if (is_array($parents) && count($parents) > 0) {
          // parents is returned as an ascending array, we need it to descend
          $parents = array_reverse($parents);
            $rootParent = array_shift($parents);
            $rootParentId = $rootParent->id;
        } else {
          //this page is a root level page.
          $rootParentId = $page->getId();
        }

        if ($rootParentId > 0) {
            return $this->view->RenderMenu($rootParentId, $levels, null, $id);
        }
    }
}