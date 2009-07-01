<?php
/**
 *
 * @author forrest lyman
 * @version
 */
require_once 'Zend/View/Interface.php';

/**
 * SelectLanguage helper
 *
 * @uses viewHelper Digitalus_View_Helper_Content
 */
class Digitalus_View_Helper_Content_SelectLanguage {

    /**
     * @var Zend_View_Interface
     */
    public $view;

    /**
     *
     */
    public function selectLanguage($name, $value, $attribs = null)
    {
        //select version
        $config = Zend_Registry::get('config');
        $siteVersions = $config->language->translations;

        foreach ($siteVersions as $locale => $label) {
            $data[$locale] = $this->view->getTranslation($label);
        }

        return $this->view->formSelect($name, $value, $attribs, $data);
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