<?php

class DSF_Toolbox_Page {

    public static function getUrl(Zend_Db_Table_Row $page, $separator = '/')
    {
        $labels[] = self::getLabel($page);
        $mdlPage = new Page();
        $parents = $mdlPage->getParents($page);
        if (is_array($parents)) {
            foreach ($parents as $parent) {
                $labels[] = self::getLabel($parent);
            }
        }

        if (is_array($labels)) {
            $labels = array_reverse($labels);
            return implode($separator, $labels);
        }
    }

    public static function getLabel(Zend_Db_Table_Row $page)
    {
        if (empty($page->label)) {
            return $page->name;
        } else {
            return $page->label;
        }
    }
}

?>