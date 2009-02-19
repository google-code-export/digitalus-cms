<?php
require_once './application/modules/search/adapter/Page.php';
abstract class Search_Adapter_Abstract
{
    protected $_pages = null;
    
    public function getPages()
    {
        return $this->_pages;
    }
    
    protected function addPage($path, $title, $teaser, $content, $searchTags = null)
    {
        $this->_pages[] = new Search_Adapter_Page($path, $title, $teaser, $content, $searchTags);
    }
}
?>