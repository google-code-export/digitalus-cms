<?php
abstract class DSF_Interface_Grid_Abstract
{
    public $view;
    protected $_attribs = array('first','last','before','after');
    const FIRST = 'first';
    const LAST = 'last';
    const BEFORE = 'before';
    const AFTER = 'after';
    
    public function loadView() {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        if (null === $viewRenderer->view) {
            $viewRenderer->initView();
        }
        $view = $viewRenderer->view;
        $this->view = $view;
    }
    
}
?>