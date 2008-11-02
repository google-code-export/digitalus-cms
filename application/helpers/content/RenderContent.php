<?php
class DSF_View_Helper_Content_RenderContent
{
	public function RenderContent($block, $rowset = null, $wordCount = 0){
	    $return = $this->view->pageObj->getCleanUri();
	    $return = DSF_Toolbox_String::addUnderscores($return);
	    $return = DSF_Toolbox_String::addHyphens($return);

	        if($rowset == null){
    	       $content = $this->view->content;
	        }else{
	            $content = $rowset;
	        }
	        
    	    $xhtml = '';
    	    
    	    if($wordCount > 0){
    	      $xhtml .= $this->view->TruncateText($content->$block, $wordCount); 
    	    }else{
    	      $xhtml .= $content->$block;  
    	    }
    	    
    	    if($this->view->CurrentAdminUser()){
    	        $title = "Currently editing: " . $this->view->page->title . " > " . $block;
    	        $xhtml .= "<p><a href='/admin/page/ajax-editor/id/{$this->view->page->id}/block/{$block}/return/{$return}/tb_params/p?height=625&width=600' class='thickbox editLink' title='{$title}'>&nbsp;</a></p>";
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