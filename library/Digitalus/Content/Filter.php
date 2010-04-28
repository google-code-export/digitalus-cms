<?php
class Digitalus_Content_Filter extends Digitalus_Abstract
{
    public $tag;
    public $page;

    public function filter($content)
    {
        $this->page = Digitalus_Builder::getPage();

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