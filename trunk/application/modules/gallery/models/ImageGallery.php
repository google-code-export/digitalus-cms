<?php
class ImageGallery extends Content 
{
    /**
     * the content type
     *
     * @var string
     */
    protected $_type = "image_gallery";
    
    /**
     * the gallery row
     *
     * @var zend_db_table_rowset
     */
    protected $_gallery;
    
    /**
     * the gallery properties
     *
     * @var properties
     */
    protected $_props;
    
    /**
     * the list of the image objects
     *
     * @var dsf_data_list
     */
    protected $_images;
    
    /**
     * open the selected gallery 
     *
     * @param int $galleryId
     * @return zend_db_table_rowset
     */
    public function openGallery($galleryId)
    {
        if($galleryId > 0){
            $this->_gallery = $this->find($galleryId)->current();
            if($this->_gallery){
                //load properties (gets a dsf_data_list)
                $this->_props = new Properties($this->_gallery->id);
                $this->_images = $this->_props->getGroup('images', true);
            }
    
        }
        return $this->_gallery;  
    }
    
    /**
     * returns the current images
     *
     * @return std class
     */
    public function getImages()
    {
        return $this->_images->items;
    }
    
    /**
     * add a new image
     * if the id is not set then use the timestamp
     *
     * @param DSF_Resource_Image $image
     * @param int $id
     */
    public function addImage($image, $id = false)
    {
        if(!$id){$id = time();}
        $this->_images->addItem($id, $image);
        $this->_props->save();
    }
    
    /**
     * remove the selected image from the current gallery
     *
     * @param int $imageId
     * @param bool $deleteFile
     */
    public function removeImage($imageId, $deleteFile = true)
    {
        $currentImages = $this->getImages();
        $image = $currentImages->$imageId;
        
        if($deleteFile === true){
            //delete the files
            unlink($image->thumbPath);
            unlink($image->fullPath);
        }
        
        //remove the image object from the gallery
        unset($currentImages->$imageId);
        $this->_props->save();
    }
    
    /**
     * remove an array of images
     * if $imagesArray is null then this will remove all of the images from the gallery
     *
     * @param mixed $imagesArray
     * @param bool $deleteFile
     */
    public function removeImages($imagesArray = null, $deleteFile = true)
    {
        if(is_array($imagesArray)){
            foreach ($imagesArray as $imageId){
                $this->removeImage($imageId, $deleteFile = true);
            }
        }elseif ($imagesArray === null){
            $images = $this->getImages();
            if($images){
                foreach ($images as $id => $img){
                    $this->removeImage($id, $deleteFile = true);
                }
            }
        }
    }
    
    /**
     * updates the caption for the selected image
     *
     * @param int $imageId
     * @param string $caption
     */
    public function updateCaption($imageId, $caption)
    {
        $currentImages = $this->getImages();
        $image = $currentImages->$imageId;
        if($image){
            $image->caption = $caption;
            $this->_props->save();            
        }

    }
    
    /**
     * resort the images in the gallery
     * pass this method an array of the ids (in the order you wish them to appear in)
     *
     * @param array $orderArray
     */
    public function sortImages($orderArray)
    {
        if(is_array($orderArray)){
            $currentImages = $this->getImages();
            foreach ($orderArray as $imageId) {
            	$copies[$imageId] = clone($currentImages->$imageId);
            	//remove the image
            	$this->removeImage($imageId, false);
            }
            if(is_array($copies)){
                foreach ($copies as $id => $image) {
                    //reinsert the image in the proper position
                	$this->addImage($image, $id);
                }
            }
        }
    }
}