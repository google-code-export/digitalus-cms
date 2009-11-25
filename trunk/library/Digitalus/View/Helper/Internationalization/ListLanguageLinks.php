<?php
/**
 * ListLanguageLinks helper
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
 * @author      Forrest Lyman
 * @category    Digitalus
 * @package     Digitalus_View
 * @subpackage  Helper
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id:$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * ListLanguageLinks helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper  Digitalus_View_Helper_GetTranslation
 */
class Digitalus_View_Helper_Internationalization_ListLanguageLinks extends Zend_View_Helper_Abstract
{
    /**
     *
     */
    public function listLanguageLinks()
    {
        if ($page = Digitalus_Builder::getPage()) {
            $currentLanguage = $page->getLanguage();
            $availableLanguages = $page->getAvailableLanguages();
            $xhtml = $this->view->getTranslation('You are reading this page in') . ' ' . $this->view->getTranslation(Digitalus_Language::getFullName($currentLanguage)) . '.';

            if (is_array($availableLanguages)) {
                $languageLinks = array();
                $uri = new Digitalus_Uri();
                $base = $uri->toString();
                foreach ($availableLanguages as $locale => $name) {
                    if (!empty($locale) && $locale != $currentLanguage) {
                        $url = $base . '/p/language/' . $locale;
                        $languageLinks[] = '<a href="' . $url . '">' . $this->view->getTranslation($name) . '</a>';
                    }
                }

                if (is_array($languageLinks) && count($languageLinks) > 0) {
                    foreach ($languageLinks as $language) {
                        $languageLinksTranslated[] = $this->view->getTranslation($language);
                    }
                    $xhtml .= ' ' . $this->view->getTranslation('This page is also translated into') . ' ' . implode(', ', $languageLinks);
                }
            }
            return '<p>' . $xhtml . '</p>';
        }
    }
}