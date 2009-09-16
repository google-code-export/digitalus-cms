<?php
require_once './application/modules/slideshow/forms/Show.php';
require_once './application/modules/slideshow/forms/Slide.php';
require_once './application/modules/slideshow/models/Show.php';
require_once './application/modules/slideshow/models/Slide.php';

class Mod_Slideshow_ShowController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->breadcrumbs = array(
           $this->view->getTranslation('Modules') => $this->getFrontController()->getBaseUrl() . '/admin/module',
           $this->view->getTranslation('Slideshow') => $this->getFrontController()->getBaseUrl() . '/mod_slideshow'
        );
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->getFrontController()->getBaseUrl() . '/admin/index/bookmark/url/mod_slideshow';

    }

    public function createAction()
    {
        $form = new Show_Form();
        if ($form->isValid($_POST)) {
            $values = $form->getValues();
            $mdlShow = new Slideshow_Show();
            $show = $mdlShow->createShow($values['name']);
            $this->_request->setParam('id', $show->id);
            $this->_request->setParam('isInsert', true);
            $this->_forward('edit');
        } else {
            $this->_forward('index', 'index');
        }
    }

    public function editAction()
    {
        $form = new Show_Form();
        $mdlShow = new Slideshow_Show();
        if ($this->_request->isPost() && $form->isValid($_POST) && $this->_request->getParam('isInsert') != true) {
            $values = $form->getValues();
            $results = $mdlShow->updateShow($values['id'], $values['name'], $values['description']);
            $show = $results->page;
        } else {
            $id = $this->_request->getParam('id');
            $show = $mdlShow->find($id)->current();
            $form->populate($show->toArray());
        }
        $form->setAction('/mod_slideshow/show/edit');
        $submit = $form->getElement('submit');
        $submit->setLabel($this->view->getTranslation('Update Slideshow'));

        $this->view->form = $form;
        $this->view->show = $show;

        $mdlSlide = new Slideshow_Slide();
        $this->view->slides = $mdlSlide->getSlides($show->id);

        $slideForm = new Slide_Form();
        $slideForm->removeElement('image');
        $slideForm->removeElement('imagepath');
        $slideForm->removeElement('previewpath');
        $slideForm->removeElement('image_preview');
        $slideForm->removeElement('caption');
        $slideFormValues['show_id'] = $show->id;
        $slideForm->populate($slideFormValues);
        $slideForm->setAction('/mod_slideshow/slide/create');
        $submit = $slideForm->getElement('submit');
        $submit->setLabel($this->view->getTranslation('Add New Slide'));
        $this->view->slideForm = $slideForm;

        $this->view->breadcrumbs[$show->name] = $this->getFrontController()->getBaseUrl() . '/mod_slideshow/show/edit/id/' . $show->id;
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->getFrontController()->getBaseUrl() . '/admin/index/bookmark/url/mod_slideshow/show/edit/id/' . $show->id;
        $this->view->toolbarLinks['Delete'] = $this->getFrontController()->getBaseUrl() . '/mod_slideshow/show/delete/id/' . $show->id;

    }

    public function deleteAction()
    {
        $id = $this->_request->getParam('id');
        $mdlShow = new Slideshow_Show();
        $mdlShow->deleteShow($id);
        $this->_forward('index', 'index');
    }

}