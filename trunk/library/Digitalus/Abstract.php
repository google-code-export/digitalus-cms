<?php
abstract class Digitalus_Abstract
{
    public $view;

    public function getView()
    {
        return $this->view;
    }

    public function setView(Zend_View $view = null)
    {
       if ($view == null) {
            $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
            if (null === $viewRenderer->view) {
                $viewRenderer->initView();
            }
            $this->view = $viewRenderer->view;
        } else {
            $this->view = $view;
        }
    }

}