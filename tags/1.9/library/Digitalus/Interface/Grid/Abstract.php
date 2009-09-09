<?php
abstract class Digitalus_Interface_Grid_Abstract
{
    public $view;
    protected $_attribs = array('first', 'last', 'before', 'after', 'clear', 'class');
    const FIRST = 'first';
    const LAST = 'last';
    const BEFORE = 'before';
    const AFTER = 'after';
    const CLEAR = 'clear';

    public function init(){}

    public function loadView()
    {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        if (null === $viewRenderer->view) {
            $viewRenderer->initView();
        }
        $view = $viewRenderer->view;
        $this->view = $view;
    }

}