<?php
class DSF_View_Helper_Content_RenderContent
{
	public function RenderContent($block, $rowset = null, $wordCount = 0){
        if($rowset == null){
           $content = $this->view->page->getContent();
        }else{
            $content = $rowset;
        }
        
        $xhtml = '';
        
        if($wordCount > 0){
          $xhtml .= $this->view->TruncateText($content->$block, $wordCount); 
        }else{
          $xhtml .= $content->$block;  
        }
            	    
    	return stripslashes($xhtml);
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