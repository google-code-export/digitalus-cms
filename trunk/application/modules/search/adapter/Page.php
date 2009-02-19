<?php
class Search_Adapter_Page
{
    public $path;
    public $title;
    public $teaser;
    public $content;
    public $searchTags;
    
    public function __construct($path, $title, $teaser, $content, $searchTags = null)
    {
        $this->path = $path;
        $this->title = $title;
        $this->teaser = $teaser;
        $this->content = $content;
        $this->searchTags = $searchTags;
    }
    
    public function asLuceneDocument()
    {
        $doc = new Zend_Search_Lucene_Document();
        $doc->addField(Zend_Search_Lucene_Field::Text('page_title', $this->title));
        $doc->addField(Zend_Search_Lucene_Field::Text('page_link', $this->path));
        $doc->addField(Zend_Search_Lucene_Field::Text('page_teaser', $this->teaser));
        $doc->addField(Zend_Search_Lucene_Field::unstored('page_content', $this->content));
        $doc->addField(Zend_Search_Lucene_Field::UnStored('search_tags', $this->searchTags));
        return $doc;
    }
}
?>