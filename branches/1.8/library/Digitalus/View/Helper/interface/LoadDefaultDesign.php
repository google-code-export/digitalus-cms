<?php
/**
 *
 * @author forrest lyman
 * @version
 */
require_once 'Zend/View/Interface.php';

/**
 * LoadDefaultDesign helper
 *
 * @uses viewHelper Digitalus_View_Helper_Interface
 */
class Digitalus_View_Helper_Interface_LoadDefaultDesign {

    /**
     * @var Zend_View_Interface
     */
    public $view;

    /**
     *
     */
    public function loadDefaultDesign()
    {
        $mdlDesign = new Model_Design();
        $design = $mdlDesign->getDefaultDesign();
        $mdlDesign->setDesign($design->id);

        //todo: this is duplicated in the builder

        //the design model returns the stylesheets organized by skin
        $skins = $mdlDesign->getStylesheets();
        if (is_array($skins)) {
            foreach ($skins as $skin => $styles) {
                if (is_array($styles)) {
                    foreach ($styles as $style) {
                        $this->view->headLink()->appendStylesheet('/skins/' . $skin . '/styles/' . $style);
                    }
                }
            }
        }

        $this->view->layout = $mdlDesign->getLayout();
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