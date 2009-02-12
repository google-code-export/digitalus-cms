<?php
class Mod_Contact_IndexController extends Zend_Controller_Action
{


    public function init()
    {
        $this->view->breadcrumbs = array(
           $this->view->GetTranslation('Modules') => $this->getFrontController()->getBaseUrl() . '/admin/module',
           $this->view->GetTranslation('Contact') => $this->getFrontController()->getBaseUrl() . '/mod_contact'
        );
        $this->view->toolbarLinks[$this->view->GetTranslation('Add to my bookmarks')] = $this->getFrontController()->getBaseUrl() . '/admin/index/bookmark/url/mod_contact';

    }

    public function indexAction()
    {

    }

}