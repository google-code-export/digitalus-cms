<?php
class DSF_Interface_Grid_ContentWrapper extends DSF_Interface_Grid_Abstract
{
    public $parentId;
    public $content;

    public function __construct($parentId)
    {
        $this->parentId = $parentId;
    }

    public function render()
    {
        $contentKey = $this->parentId . '_content';
    	if($this->content == null) {
	        //load the content from the placeholder
	        $this->loadView();
	        $this->content = $this->view->placeholder($contentKey)->toString();
    	}

        //Only need a nested container if there is content there
        if (!empty($this->content)) {
            return "<div id='{$contentKey}' class='innerContent'>{$this->content}</div>";
        } else {
            return null;
        }
    }
}
?>