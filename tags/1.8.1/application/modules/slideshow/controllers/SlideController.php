<?php
require_once './application/modules/slideshow/forms/Slide.php';
require_once './application/modules/slideshow/models/Show.php';
require_once './application/modules/slideshow/models/Slide.php';

class Mod_Slideshow_SlideController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->breadcrumbs = array(
           $this->view->getTranslation('Modules') => $this->view->getBaseUrl() . '/admin/module',
           $this->view->getTranslation('Slideshow') => $this->view->getBaseUrl() . '/mod_slideshow'
        );
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->view->getBaseUrl() . '/admin/index/bookmark/url/mod_slideshow';
    }

    public function createAction()
    {
        $form = new Slide_Form();
        $form->removeElement('image_preview');
        $form->removeElement('image');
        $form->removeElement('caption');
        if ($form->isValid($_POST)) {
            $values = $form->getValues();
            $mdlSlide = new Slideshow_Slide();
            $slide = $mdlSlide->createSlide($values['show_id'], $values['title']);
            $this->_request->setParam('id', $slide->id);
            $this->_request->setParam('isInsert', true);
            $this->_forward('edit');
        } else {
            $showId = $_POST['show_id'];
            if ($showId > 0) {
                $this->_request->setParam('id', $showId);
                $this->_forward('edit', 'show');
            } else {
                $this->_forward('index','index');
            }
        }
    }

    public function editAction()
    {
        $form = new Slide_Form();
        $mdlShow = new Slideshow_Show();
        $mdlSlide = new Slideshow_Slide();

        // generate path to save file to
        $id = $this->_request->getParam('id');
        $properties = Digitalus_Module_Property::load('mod_slideshow');
        $config = Zend_Registry::get('config');
        $path = $config->filepath->media;
        $directory =  $path . '/' . $properties->mediaFolder . '/' . $mdlSlide->getShowBySlide($id);
        $directoryFull    = $directory . '/full';
        $directoryPreview = $directory . '/preview';
        // create and set file's destination directory
        Digitalus_Filesystem_dir::makeRecursive(BASE_PATH, $directoryFull);
        Digitalus_Filesystem_dir::makeRecursive(BASE_PATH, $directoryPreview);
        $form->image->setDestination($directoryFull);
        $form->image_preview->setDestination($directoryPreview);

        if ($this->_request->isPost() && $this->_request->getParam('isInsert') != true) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                // success - do something with the uploaded file
                $values      = $form->getValues();
                $imagePath   = $form->image->getFileName();
                $previewPath = $form->image_preview->getFileName();
                // some servers set different permissions, so set them explicitly here
                if (!empty($imagePath)) { chmod($imagePath, 0644); }
                if (!empty($previewPath)) { chmod($previewPath, 0644); }

                $slide = $mdlSlide->updateSlide(
                    $values['id'],
                    $values['title'],
                    $values['caption'],
                    $previewPath,
                    $imagePath
                );
                $slide = $mdlSlide->openSlide($values['id']);
            }
        }
        $slide = $mdlSlide->openSlide($id);
        $slideArray['id']          = $slide->id;
        $slideArray['blog_id']     = $slide->showId;
        $slideArray['title']       = $slide->title;
        $slideArray['previewpath'] = $slide->previewPath;
        $slideArray['imagepath']   = $slide->imagePath;
        $slideArray['caption']     = $slide->caption;

        $form->setAction($this->view->getBaseUrl() . '/mod_slideshow/slide/edit')
            ->populate($slideArray);

        $show = $mdlShow->find($slide->showId)->current();

        $submit = $form->getElement('submit');
        $submit->setLabel($this->view->getTranslation('Update Slide'));

        $this->view->form  = $form;
        $this->view->show  = $show;
        $this->view->slide = $slide;

        $this->view->breadcrumbs[$show->name] = $this->view->getBaseUrl() . '/mod_slideshow/show/edit/id/' . $show->id;
        $this->view->breadcrumbs[$slide->title] = $this->view->getBaseUrl() . '/mod_slideshow/slide/edit/id/' . $slide->id;
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->view->getBaseUrl() . '/admin/index/bookmark/url/mod_slideshow/slide/edit/id/' . $slide->id;
        $this->view->toolbarLinks['Delete'] = $this->getFrontController()->getBaseUrl() . '/mod_slideshow/slide/delete/id/' . $slide->id;
    }

    public function reorderAction()
    {
        $mdlShow = new Slideshow_Show();
        $mdlSlide = new Slideshow_Slide();
        if ($this->_request->isPost()) {
            //sort the slides
            $ids = Digitalus_Filter_Post::raw('id');
            $mdlSlide->sortSlides($ids);
            $show = Digitalus_Filter_Post::get('show');
            $url = '/mod_slideshow/show/edit/id/' . $show;
            $this->_redirect($url);
        } else {
            $show = $this->_request->getParam('show');
        }
        $this->view->slides = $mdlSlide->getSlides($show);
        $this->view->show = $mdlShow->find($show)->current();
    }

    public function deleteAction()
    {
        $mdlSlide = new Slideshow_Slide();
        $id = $this->_request->getParam('id');
        $slide = $mdlSlide->openSlide($id);
        $mdlSlide->deletePageById($id);
        $this->_request->setParam('id', $slide->showId);
        $this->_forward('edit','show');
    }
}