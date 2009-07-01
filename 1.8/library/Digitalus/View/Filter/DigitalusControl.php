<?php
class Zend_View_Filter_DigitalusControl extends Digitalus_Content_Filter 
{
    public $tag = 'digitalusControl';   

    
    protected function _callback($matches)
    {
        $attr = $this->getAttributes($matches[0]);
        if(is_array($attr)) {
            $content = $this->page->getContent();
            if(isset($content[$attr['id']])) {
                $controlContent = $content[$attr['id']];
                switch ($attr['type']) {
                    case 'fckeditor':
                        return "<div id='{$attr['id']}_wrapper'>{$controlContent}</div>";
                        break;
                    case 'text':
                        return $controlContent;
                        break;
                    case 'moduleSelector':
                        return $this->view->renderModule($controlContent);
                        break;
                }
            }
        }
        return null;
    }
}
?>