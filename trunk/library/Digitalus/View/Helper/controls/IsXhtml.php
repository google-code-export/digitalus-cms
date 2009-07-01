<?php
/**
 *
 * @author forrest lyman
 * @version
 */
require_once 'Zend/View/Interface.php';

/**
 * SelectDoctype helper
 *
 * @uses viewHelper Digitalus_View_Helper_Controls
 */
class Digitalus_View_Helper_Controls_IsXhtml
{

    /**
     * @var Zend_View_Interface
     */
    public $view;

    /**
     *
     */
    public function isXhtml($doctype = null)
    {
        if (empty($doctype)) {
            $doctype = $this->view->doctype();
        }

        if (strpos($doctype, 'XHTML')) {
            return ' xmlns="http://www.w3.org/1999/xhtml"';
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
