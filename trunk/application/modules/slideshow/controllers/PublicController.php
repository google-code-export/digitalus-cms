<?php
require_once './application/modules/slideshow/models/Show.php';
require_once './application/modules/slideshow/models/Slide.php';

class Mod_Slideshow_PublicController extends Digitalus_Controller_Action
{
    public $moduleData;
    public $properties;

    public function init()
    {
        parent::init();

        $module = new Digitalus_Module();
        $this->moduleData = $module->getData();
        $this->properties = Digitalus_Module_Property::load('mod_slideshow');
    }

    public function showAction()
    {
        $show = $this->moduleData->show;
        $page = Digitalus_Builder::getPage();
        $params = $page->getParams();
        if (!isset($params['slide']) || $params['slide'] == null) {
            $index = 1;
        } else {
            $index = $params['slide'];
        }
        $mdlSlide = new Slideshow_Slide();

        $slide = $mdlSlide->getSlideByShow($show, $index);
        $count = $mdlSlide->countSlidesInShow($show);

        $pageLinks = new stdClass();

        if ($index == 1) {
            $pageLinks->first = null;
            $pageLinks->previous = null;
        } else {
            $pageLinks->first = Digitalus_Uri::get(false, false, array('slide' => 1));
            $pageLinks->previous = Digitalus_Uri::get(false, false, array('slide' => ($index - 1)));
        }

        if ($index < $count && $count > 1) {
            $pageLinks->next = Digitalus_Uri::get(false, false, array('slide' => ($index + 1)));
            $pageLinks->last = Digitalus_Uri::get(false, false, array('slide' => ($count)));
        } else {
            $pageLinks->next = null;
            $pageLinks->last = null;
        }

        $this->view->pageLinks = $pageLinks;
        $this->view->slide = $slide;
        $this->view->index = $index;
        $this->view->count = $count;
        $this->view->slides = $mdlSlide->getSlides($show);
    }

    public function getIndexAction()
    {
        $show = $this->moduleData->show;
        $mdlSlide = new Slideshow_Slide();
        $this->view->slides = $mdlSlide->getSlides($show);
    }
}