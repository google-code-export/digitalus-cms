<?php
class DSF_View_Helper_Content_RenderContent
{
    public $contentBlocks = array('intro','content','additional_content');
	/**
	 * 
	 */
	public function RenderContent($block, $rowset = null, $wordCount = 0){
	    $return = $this->view->pageObj->getCleanUri();
	    $return = DSF_Toolbox_String::addUnderscores($return);
	    $return = DSF_Toolbox_String::addHyphens($return);

	    if(in_array($block, $this->contentBlocks)){
	        if($rowset == null){
    	       $page = $this->view->page;
	        }else{
	            $page = $rowset;
	        }
    	    
    	    $xhtml = '';
    	    
    	    if($wordCount > 0){
    	      $xhtml .= $this->view->TruncateText($page->$block, $wordCount); 
    	    }else{
    	      $xhtml .= $page->$block;  
    	    }
    	    
    	    if($this->view->CurrentAdminUser()){
    	        $title = "Currently editing: " . $page->title . " > " . $block;
    	        $xhtml .= "<p><a href='/admin/page/ajax-editor/id/{$page->id}/block/{$block}/return/{$return}/tb_params/p?height=625&width=600' class='thickbox editLink' title='{$title}'>&nbsp;</a></p>";
    	    }
    	    
    		return stripslashes($xhtml);
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