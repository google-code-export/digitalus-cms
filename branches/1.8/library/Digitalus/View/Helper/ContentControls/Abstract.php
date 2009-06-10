<?php
class Digitalus_View_Helper_ContentControls_Abstract extends Zend_View_Helper_Abstract 
{
    public $params = array();
    public $defaultParams = array();
    
    public function getValue($key)
    {
        if(isset($this->params[$key])) {
            return $this->params[$key];
        }elseif(isset($this->defaultParams[$key])) {
            return $this->defaultParams[$key];
        }else{
            return null;
        }
    }
}
?>