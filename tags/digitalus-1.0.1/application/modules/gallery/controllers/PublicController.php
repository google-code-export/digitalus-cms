<?php
require('./application/modules/gallery/models/ImageGallery.php');

class Mod_Gallery_PublicController extends DSF_Controller_Module_Public 
{
    //add methods for your module here
    
    public function simplegalleryAction()
    {
        $gallery = new ImageGallery();        
        $id = $this->_request->getParam('gallery');
        $this->view->gallery = $gallery->openGallery($id);
        $images = $gallery->getImages();
        $this->view->images = $images;
    }
}