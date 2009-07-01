<?php
class Zend_View_Filter_DigitalusModule extends Digitalus_Content_Filter 
{
    public $tag = 'digitalusModule';   
    
    protected function _callback($matches)
    {
        $attr = $this->getAttributes($matches[0]);
        if(is_array($attr)) {
            return  $this->view->action($attr['action'], 'public', 'mod_' . $attr['module'], $attr);
        }
        return null;
    }
}
?>