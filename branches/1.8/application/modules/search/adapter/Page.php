<?php
class Search_Adapter_Page
{
    public $path;
    public $title;
    public $teaser;
    public $content;
    public $searchTags;
    protected $_charset;

    public function __construct($path, $title, $teaser, $content, $searchTags = null)
    {
        $this->path = $path;
        $this->title = $title;
        $this->teaser = $teaser;
        $this->content = $content;
        $this->searchTags = $searchTags;
        $this->_getCharset();
    }

    public function asLuceneDocument()
    {
        $doc = new Zend_Search_Lucene_Document();
        $doc->addField(Zend_Search_Lucene_Field::Text('page_title', $this->title, $this->_charset));
        $doc->addField(Zend_Search_Lucene_Field::Text('page_link', $this->path, $this->_charset));
        $doc->addField(Zend_Search_Lucene_Field::Text('page_teaser', $this->teaser, $this->_charset));
        $doc->addField(Zend_Search_Lucene_Field::unstored('page_content', $this->content, $this->_charset));
        $doc->addField(Zend_Search_Lucene_Field::UnStored('search_tags', $this->searchTags, $this->_charset));
        return $doc;
    }

    protected function _getCharset()
    {
        $settings = new Model_SiteSettings();
        $this->_charset = $settings->get('default_charset');
    }
}
?>