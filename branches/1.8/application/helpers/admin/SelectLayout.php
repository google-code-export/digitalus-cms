<?php
/**
 *
 * @author forrest lyman
 * @version
 */
require_once 'Zend/View/Interface.php';

/**
 * SelectLayout helper
 *
 * @uses viewHelper Digitalus_View_Helper_Admin
 */
class Digitalus_View_Helper_Admin_SelectLayout {

    /**
     * @var Zend_View_Interface
     */
    public $view;

    /**
     *
     */
    public function selectLayout($name, $value = null, $attr = null, $defaut = null) {
        $config = Zend_Registry::get('config');
        $pathToPublicLayouts = $config->design->pathToPublicLayouts;
        $layouts = Digitalus_Filesystem_File::getFilesByType($pathToPublicLayouts, 'phtml');
        if ($defaut == NULL) {
            $defaut = $this->view->getTranslation('Select One');
        }
        $options[0] = $defaut;

        if (is_array($layouts)) {
            foreach ($layouts as $layout) {
                $options[$layout] = $layout;
            }
            return $this->view->formSelect($name, $value, $attr, $options);
        } else {
            return null;
        }

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