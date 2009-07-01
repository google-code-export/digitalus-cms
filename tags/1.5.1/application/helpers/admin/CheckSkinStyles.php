<?php
/**
 *
 * @author forrest lyman
 * @version
 */
require_once 'Zend/View/Interface.php';

/**
 * CheckSkinStyles helper
 *
 * @uses viewHelper DSF_View_Helper_Admin
 */
class DSF_View_Helper_Admin_CheckSkinStyles {

    /**
     * @var Zend_View_Interface
     */
    public $view;
    public $partialFile = 'design/listSkin.phtml';

    /**
     *
     */
    public function checkSkinStyles($name, $values) {
        $config = Zend_Registry::get('config');
        $basePath = $config->design->pathToSkins;
        $xhtml = array();
        $this->view->name = $name;
        $this->view->selectedStyles = $values;

        //load the skin folders
        if (is_dir('./' . $basePath)) {
            $folders = DSF_Filesystem_Dir::getDirectories('./' . $basePath);
            if (count($folders) > 0) {
                foreach ($folders as $folder) {
                    $this->view->skin = $folder;
                    $styles = DSF_Filesystem_File::getFilesByType('./' . $basePath . '/' . $folder . '/styles', 'css');
                    if (is_array($styles)) {
                        foreach ($styles  as $style) {
                            //add each style sheet to the hash
                            // key = path / value = filename
                            $hashStyles[$style] = $style;
                        }
                        $this->view->styles = $hashStyles;
                        $xhtml[] = $this->view->render($this->partialFile);
                        unset($hashStyles);
                    }
                }
            }
        } else {
            throw new Zend_Acl_Exception('Unable to locate skin folder');
        }

        return implode(null, $xhtml);

    }

    /**
     * Sets the view field
     * @param $view Zend_View_Interface
     */
    public function setView(Zend_View_Interface $view) {
        $this->view = $view;
    }
}