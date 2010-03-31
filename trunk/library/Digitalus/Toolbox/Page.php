<?php
class Digitalus_Toolbox_Page
{
    public static function getUrl(Zend_Db_Table_Row $page, $separator = '/')
    {
        $labels[] = $page->name;
        $mdlPage = new Model_Page();
        $parents = $mdlPage->getParents($page);
        if (is_array($parents)) {
            foreach ($parents as $parent) {
                $labels[] = $parent->name;
            }
        }
        if (is_array($labels)) {
            $labels = array_reverse($labels);
            return implode($separator, $labels);
        }
    }

    public static function getLabel(Zend_Db_Table_Row $page)
    {
        $mdlPage = new Model_Page();
        $label = $mdlPage->getLabelById($page->id);
        if (empty($label)) {
            return $page->name;
        }
        return $label;
    }
}