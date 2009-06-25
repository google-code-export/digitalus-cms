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
class Digitalus_View_Helper_Admin_SelectDesign extends Zend_View_Helper_Abstract {

    /**
     *
     */
    public function selectDesign($name, $value = null, $attr = null) {
        $templateConfig = Zend_Registry::get('config')->template;        
        $templates = Digitalus_Filesystem_Dir::getDirectories(BASE_PATH . '/' . $templateConfig->pathToTemplates . '/public');
        foreach ($templates as $template) {
            $designs = Digitalus_Filesystem_File::getFilesByType(BASE_PATH . '/' . $templateConfig->pathToTemplates . '/public/' . $template . '/pages', 'xml');
            if(is_array($designs)) {
                foreach ($designs as $design) {
                    $design = Digitalus_Toolbox_Regex::stripFileExtension($design);
                    $options[$template . '_' . $design] = $template . ' / ' . $design;
                }
            }
        }

        return $this->view->formSelect($name, $value, $attr, $options);

    }
}