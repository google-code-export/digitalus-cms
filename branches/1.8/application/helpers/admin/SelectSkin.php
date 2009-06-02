<?php
/**
 *
 * @author forrest lyman
 * @version
 */
require_once 'Zend/View/Interface.php';

/**
 * SelectSkin helper
 *
 * @uses viewHelper Digitalus_View_Helper_Admin
 */
class Digitalus_View_Helper_Admin_SelectSkin {

    /**
     * @var Zend_View_Interface
     */
    public $view;

    /**
     *
     */
    public function selectSkin($name, $value = null, $attr = null, $defaut = null) {
        $config = Zend_Registry::get('config');
        $pathToPublicSkins = $config->design->pathToSkins;
        $skins = Digitalus_Filesystem_Dir::getDirectories($pathToPublicSkins);
        if ($defaut == NULL) {
            $defaut = $this->view->getTranslation('Select One');
        }
        $options[0] = $defaut;

        if (is_array($skins)) {
            foreach ($skins as $skin) {
                $options[$skin] = $skin;
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