<?php
/**
 *
 * @author forrest lyman
 * @version
 */
require_once 'Zend/View/Interface.php';

/**
 * getData helper
 *
 * @uses viewHelper Digitalus_View_Helper_General
 */
class Digitalus_View_Helper_General_getData {

    /**
     * @var Zend_View_Interface
     */
    public $view;

    /**
     *
     */
    public function getData($field, $dataSet = null)
    {
        if (is_array($dataSet)) {
            if (isset($dataSet[$field])) {
                return $dataSet[$field];
            }
        } elseif (is_object($dataSet)) {
            if (isset($dataSet->$field)) {
                return $dataSet->$field;
            }
        } else {
            return $this->view->$field;
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
