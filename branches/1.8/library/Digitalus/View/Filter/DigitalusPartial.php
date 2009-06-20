<?php
class Zend_View_Filter_DigitalusPartial extends Digitalus_Content_Filter 
{
    public $tag = 'digitalusPartial';   

    
    protected function _callback($matches)
    {
        $attr = $this->getAttributes($matches[0]);
        if(is_array($attr)) {
            return $this->view->render($attr['src']);
        }
        return null;
    }
}
?>