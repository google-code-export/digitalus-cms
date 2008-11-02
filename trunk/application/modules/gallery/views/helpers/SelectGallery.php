<?php
require('./application/modules/gallery/models/ImageGallery.php');
class Zend_View_Helper_SelectGallery
{	
    /**
     * values should be an csv list of the ids of the Gallery
     *
     * @param unknown_type $values
     */
	public function SelectGallery($name, $value, $attribs = null)
	{
	    $p = new ImageGallery();
	    $galleries = $p->fetchAll(null, 'title');
	    
	    if($galleries){
	        foreach ($galleries as $gallery){
	            $data[$gallery->id] = $gallery->title;
	        }
	        if(is_array($data)){
	            return $this->view->formSelect($name, $value, $attribs, $data);
	        }
	    }
	}
	
    /**
     * Set this->view object
     *
     * @param  Zend_this->view_Interface $this->view
     * @return Zend_this->view_Helper_DeclareVars
     */
    public function setview(Zend_view_Interface $view)
    {
        $this->view = $view;
        return $this;
    }
}