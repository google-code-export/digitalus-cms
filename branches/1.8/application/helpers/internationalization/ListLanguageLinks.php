<?php
/**
 *
 * @author forrest lyman
 * @version
 */
require_once 'Zend/View/Interface.php';

/**
 * ListLanguageLinks helper
 *
 * @uses viewHelper Digitalus_View_Helper_Internationalization
 */
class Digitalus_View_Helper_Internationalization_ListLanguageLinks {

    /**
     * @var Zend_View_Interface
     */
    public $view;

    /**
     *
     */
    public function listLanguageLinks()
    {
        $page = Digitalus_Builder::getPage();
        $currentLanguage = $page->getLanguage();
        $availableLanguages = $page->getAvailableLanguages();
        $xhtml = $this->view->getTranslation('You are reading this page in') . ' ' . $this->view->getTranslation(Digitalus_Language::getFullName($currentLanguage)) . '.';

        if (is_array($availableLanguages)) {
            $languageLinks = array();
            $uri = new Digitalus_Uri();
            $base = $uri->toString();
            foreach ($availableLanguages as $locale => $name) {
                if (!empty($locale) && $locale != $currentLanguage) {
                    $url = $base. '/p/lang/' . $locale;
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

    /**
     * Sets the view field
     * @param $view Zend_View_Interface
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }
}