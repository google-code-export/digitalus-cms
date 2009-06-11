<?php
class Digitalus_View_Helper_ModuleControl extends Digitalus_View_Helper_ContentControl 
{
    public function moduleControl($name, $value)
    {
        return parent::contentControl('moduleselector', $name, $value);
    }
    
    public function render()
    {
        return $this->view->RenderModule($this->content[$this->id]);
    }
}
?>