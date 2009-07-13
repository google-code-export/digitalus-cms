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
        if ($this->_request->isPost() && $form->isValid($_POST) && $this->_request->getParam('isInsert') != true){
            $values = $form->getValues();

            //upload the image
            $preview = $_FILES['image_preview'];
            $previewPath = null;
            if (isset($preview)) {
                $properties = Digitalus_Module_Property::load('mod_slideshow');
                $folder =  $properties->mediaFolder . '/' . $mdlSlide->getShowBySlide($values['id']) .'/preview';
                if ($preview) {
                    $previewPath = Digitalus_Media::upload($preview, $folder, null);

                }
            }

            //upload the image
            $file = $_FILES['image'];
            $imagePath = null;
            if (isset($file)) {
                $properties = Digitalus_Module_Property::load('mod_slideshow');
                $path =  $properties->mediaFolder . '/' . $mdlSlide->getShowBySlide($values['id']) . '/full' ;
                if ($file) {
#                    $imagePath = Digitalus_Media::upload($file, $path, null);
                    $imagePath = Digitalus_Media::upload($file, $path, $file['name']);
#                   echo "here is the problem, <br>
#                        Media library upload.php never returns cause never move filename from tmp $imagePath";
                }
            }
            $slide = $mdlSlide->updateSlide(
                $values['id'],
                $values['title'],
                $values['caption'],
                $previewPath,
                $imagePath
            );

            $slide = $mdlSlide->openSlide($values['id']);
        } else {
            $id = $this->_request->getParam('id');
            $slide = $mdlSlide->openSlide($id);
        }

        $slideArray['id'] = $slide->id;
        $slideArray['blog_id'] = $slide->showId;
        $slideArray['title'] = $slide->title;
        $slideArray['previewpath']= $slide->previewPath;
        $slideArray['imagepath']= $slide->imagePath;
        $slideArray['caption'] = $slide->caption;
        $form->populate($slideArray);

        $show = $mdlShow->find($slide->showId)->current();

        $form->setAction($this->view->getBaseUrl() . '/mod_slideshow/slide/edit');
        $submit = $form->getElement('submit');
        $submit->setLabel($this->view->getTranslation('Update Slide'));

        $this->view->form = $form;
        $this->view->show = $show;
        $this->view->slide = $slide;

        $this->view->breadcrumbs[$show->name] = $this->view->getBaseUrl() . '/mod_slideshow/show/edit/id/' . $show->id;
        $this->view->breadcrumbs[$slide->title] = $this->view->getBaseUrl() . '/mod_slideshow/slide/edit/id/' . $slide->id;
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