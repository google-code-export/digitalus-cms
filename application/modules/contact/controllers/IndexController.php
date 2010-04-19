<?php
class Mod_Contact_IndexController extends Digitalus_Controller_Action
{
    public function init()
    {
        parent::init();

        $this->view->breadcrumbs = array(
           $this->view->GetTranslation('Modules') => $this->baseUrl . '/admin/module',
           $this->view->GetTranslation('Contact') => $this->baseUrl . '/mod_contact'
        );
        $this->view->toolbarLinks[$this->view->GetTranslation('Add to my bookmarks')] = $this->baseUrl . '/admin/index/bookmark'
            . '/url/mod_contact'
            . '/label/' . $this->view->GetTranslation('Module') . ':' . $this->view->GetTranslation('Contact');
    }

    public function indexAction()
    {

    }

}