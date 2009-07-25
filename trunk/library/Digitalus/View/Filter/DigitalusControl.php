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
                        $xhtml = "<div id='{$attr['id']}_wrapper'>{$controlContent}</div>";
                        break;
                    case 'text' || 'textarea':
                        $xhtml = $controlContent;
                        break;
                    case 'moduleSelector':
                        $xhtml = $this->view->renderModule($controlContent);
                        break;
                }
                if(isset($attr['tag']) && !empty($xhtml)) {
                    return "<{$attr['tag']}>" . $xhtml . "</{$attr['tag']}>";
                }else{
                    return $xhtml;
                }
            }
        }
        return null;
    }
}
?>