<?php
class DSF_Interface_Grid_ContentWrapper extends DSF_Interface_Grid_Abstract
{
    public $parentId;

    public function __construct($parentId)
    {
        $this->parentId = $parentId;
    }

    public function render()
    {
        //load the content from the placeholder
        $this->loadView();
        $contentKey = $this->parentId . '_content';
        $content = $this->view->placeholder($contentKey)->toString();

        //Only need a nested container if there is content there
        if (!empty($content)) {
            return "<div id='{$contentKey}' class='innerContent'>{$content}</div>";
        } else {
            return null;
        }
    }
}
?>