<?php
class Mod_Contact_IndexController extends Zend_Controller_Action
{


    public function init()
    {
        $this->view->breadcrumbs = array(
           $this->view->getTranslation('Modules') => $this->getFrontController()->getBaseUrl() . '/admin/module',
           $this->view->getTranslation('Contact') => $this->getFrontController()->getBaseUrl() . '/mod_contact'
        );
        $this->view->toolbarLinks[$this->view->getTranslation('Add to my bookmarks')] = $this->getFrontController()->getBaseUrl() . '/admin/index/bookmark'
            . '/url/mod_contact'
            . '/label/' . $this->view->getTranslation('Module') . ':' . $this->view->getTranslation('Contact');
    }

    public function indexAction()
    {

    }

}