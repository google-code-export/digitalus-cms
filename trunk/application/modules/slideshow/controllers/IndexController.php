<?php
require_once './application/modules/slideshow/forms/Show.php';
require_once './application/modules/slideshow/models/Show.php';

class Mod_Slideshow_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->breadcrumbs = array(
           $this->view->getTranslation('Modules') => $this->getFrontController()->getBaseUrl() . '/admin/module',
           $this->view->getTranslation('Slideshow') => $this->getFrontController()->getBaseUrl() . '/mod_slideshow'
        );
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->getFrontController()->getBaseUrl() . '/admin/index/bookmark/url/mod_slide';
    }

    public function indexAction()
    {
        $showForm = new Show_Form();
        $showForm->removeElement('description');
        $showForm->setAction('/mod_slideshow/show/create');
        $submit = $showForm->getElement('submit');
        $submit->setLabel($this->view->getTranslation('Create Slideshow'));
        $this->view->form = $showForm;
        $mdlSlideshow = new Slideshow_Show();
        $this->view->slideshows = $mdlSlideshow->getShows();
    }
}