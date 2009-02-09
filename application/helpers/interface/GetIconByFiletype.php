<?php
/**
 *
 * @author forrest lyman
 * @version
 */
require_once 'Zend/View/Interface.php';

/**
 * GetIconByType helper
 *
 * @uses viewHelper DSF_View_Helper_Interface
 */
class DSF_View_Helper_Interface_GetIconByFileType {

    /**
     * @var Zend_View_Interface
     */
    public $view;
    public $defaultIcon = 'page.png';
    public $folderIcon = 'folder.png';
    public $icons = array();
    /**
     *
     */
    public function getIconByFileType($file, $asImage = true)
    {
        $config = Zend_Registry::get('config');
        $this->icons = $config->filetypes;
        $icon = $this->getIcon($file);
        if ($asImage) {
            $base = $this->view->baseUrl . '/' . $config->filepath->icons;
            return "<img src='{$base}/{$icon}' />";
        } else {
            return $icon;
        }
    }

    public function getIcon($file)
    {
        $filetype = DSF_Media_Filetype::load($file);
        $type = $filetype->key;

        if (isset($this->icons->$type)) {
            $filetype = $this->icons->$type;
            return $filetype->icon;
        } else {
            return $this->defaultIcon;
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