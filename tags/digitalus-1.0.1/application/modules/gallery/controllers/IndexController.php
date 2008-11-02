<?php
require('./application/modules/gallery/models/ImageGallery.php');

class Mod_Gallery_IndexController extends DSF_Controller_Module_Abstract 
{
    protected $_addMessage = "More descriptive message";
    protected $_moduleName = "gallery";
    protected $_controllerName = "index";
    protected $_modelClass = "ImageGallery";
    
    public function indexAction()
    {
        $this->view->galleries = $this->_model->fetchAll(null, 'title');
    }
    
    public function beforeOpen()
    {
        $id = $this->_request->getParam('id');
        $gallery = $this->_model->openGallery($id);
        $this->view->images = $this->_model->getImages();
    }
    
    public function onDelete()
    {
        //remove all of the images from the filesystem
        $this->_model->openGallery($this->_recordId);
        $this->_model->removeImages();
        
        //remove the gallery directory
        rmdir('./public/resources/images/gallery_' . $this->_recordId);
    }
    
    public function addImageAction()
    {
        if($this->_request->isPost()){
            $id = DSF_Filter_Post::int('id');
            $gallery = $this->_model->openGallery($id);
            
            $img = new DSF_Resource_Image();
            $img->upload('image', 'gallery_' . $id);
            
            $img->caption = DSF_Filter_Post::get('caption');
            $this->_model->addImage($img);
        }
        $url = "/mod_gallery/index/edit/id/" . $id;
        $this->_redirect($url);
    }
    
    public function updateImagesAction()
    {
        Zend_Debug::dump($_POST);
        //load the gallery
        $galleryId = DSF_Filter_Post::int('id');
        $gallery = $this->_model->openGallery($galleryId);
            
        $images = DSF_Filter_Post::raw('imageId');
        $imagesToRemove = DSF_Filter_Post::raw('removeImage');
        $captions = DSF_Filter_Post::raw('caption');
        
        if(is_array($images)){
            //sort the images
            $this->_model->sortImages($images);
            
            //update captions 
            foreach ($captions as $id => $caption){
                $this->_model->updateCaption($id, $caption);
            }
            
            //remove any images that were selected
            foreach ($imagesToRemove as $id => $remove){
                if($remove == 1){
                    $this->_model->removeImage($id, false);
                }
            }
        }
        $url = "/mod_gallery/index/edit/id/" . $galleryId;
        $this->_redirect($url);
    }
}


