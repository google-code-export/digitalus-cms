<?php
/**
 *
 * @author forrest lyman
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * Grid960 helper
 *
 * @uses viewHelper DSF_View_Helper_Controls
 */
class DSF_View_Helper_Controls_Grid960 {
    
    /**
     * @var Zend_View_Interface 
     */
    public $view;
    
    public $container = null;
    public $containerClass = 'container';
    public $unitClass = 'grid';
    
    /**
     * this class wraps a grid960 container.  
     * you create the container in the constructor
     * then add units to it at will.
     * @param int $containerWidth
     */
    public function grid960($columns, $before = 0, $after = 0) {
        
        $div = "<div />";
        $this->container = simplexml_load_string($div);
        $class = $this->makeClass($columns,$this->containerClass, $before, $after, false, false);
        $this->container->addAttribute('class', $class);
        return $this;
    }
    
    public function startRow($columns, $parent = null, $before = 0, $after = 0)
    {
        return $this->_addUnit($columns, $parent, $before, $after, true, false);
    }
    
    public function addUnit($columns, $parent = null, $before = 0, $after = 0)
    {
        return $this->_addUnit($columns, $parent, $before, $after, false, false);
     
    }
    
    public function endRow($columns, $parent = null, $before = 0, $after = 0)
    {
        return $this->_addUnit($columns, $parent, $before, $after, false, true);
    }
    
    public function populate($element, $content, $wrapper = "div")
    {
        $element->addChild($wrapper, $content);
    }
    
    protected function _addUnit($columns, $parent = null, $before = 0, $after = 0, $first = false, $last = false)
    {
        if($parent == null) {
            $div = $this->container->addChild('div');
        }else{
            $div = $parent->addChild('div');
        }
                
        $class = $this->makeClass($columns, $this->unitClass, $before, $after, $first, $last);
        $div->addAttribute('class', $class);
        return $div;
    }
    
    public function render()
    {
        return $this->container->asXml();
    }
    
    public function makeClass($columns, $type = null, $before = 0, $after = 0, $first = false, $last = false)
    {
        $class = array();
        
        if($type != null) {
            $baseClass = $type;
        }else{
            $baseClass = $this->unitClass;
        }
        
        $class[] = $baseClass . '_' . $columns;
    
        if($first == true) {
            $class[] = "alpha";
        }elseif($last == true) {
            $class[] = "omega";
        }
        
        if($before > 0) {
            $class[] = "prefix_" . $before;
        }
        
        if($after > 0) {
            $class[] = "suffix_" . $after;
        }
        
        return implode(' ', $class);
        
    }
    
    /**
     * Sets the view field 
     * @param $view Zend_View_Interface
     */
    public function setView(Zend_View_Interface $view) {
        $this->view = $view;
    }
}
