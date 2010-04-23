<?php
class Digitalus_Content_Filter
{
    public $tag;
    public $page;
    public $view;

    public function filter($content)
    {
        $this->page = Digitalus_Builder::getPage();
        $this->view = $this->page->getView();
        $pattern = '(\<' . $this->tag . '(/?[^\>]+)\>)';
        $content = preg_replace_callback($pattern, array($this, '_callback'), $content);
        return $content;
    }

    public function getAttributes($element)
    {
        $xml = @simplexml_load_string($element);
        if ($xml) {
            foreach ($xml->attributes() as $key => $value) {
                $attr[$key] = (string)$value;
            }
            return $attr;
        } else {
            return null;
        }
    }
}