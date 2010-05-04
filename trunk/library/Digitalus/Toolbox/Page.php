<?php
/**
 * Digitalus CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://digitalus-media.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@digitalus-media.com so we can send you a copy immediately.
 *
 * @author      Forresst Lyman
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_Toolbox
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: Page.php Tue Dec 25 21:17:02 EST 2007 21:17:02 forrest lyman $
 */

class Digitalus_Toolbox_Page
{
    public static function getUrl(Zend_Db_Table_Row $page, $separator = '/')
    {
        $labels[] = $page->name;
        $mdlPage  = new Model_Page();
        $parents  = $mdlPage->getParents($page);
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
        $label   = $mdlPage->getLabelById($page->id);
        if (empty($label)) {
            return $page->name;
        }
        return $label;
    }

    public static function getCurrentPageName($onlyLast = true)
    {
        $uri       = new Digitalus_Uri();
        $uriString = $uri->toString();
        if (true === $onlyLast) {
            return Digitalus_Toolbox_String::getSelfFromPath($uriString);
        }
        return Digitalus_Toolbox_String::stripLeading('/', $uriString);
    }

    public static function getHomePageName()
    {
        $mdlPage    = new Model_Page();
        $homepageId = $mdlPage->getHomePage();
        return $mdlPage->getLabelById($homepageId);
    }
}