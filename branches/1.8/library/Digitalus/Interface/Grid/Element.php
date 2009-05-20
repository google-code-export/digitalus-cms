<?php
class Digitalus_Interface_Grid_Element extends Digitalus_Interface_Grid_Abstract
{
    public $parentElement;
    public $id;
    public $columns;
    public $attr = array();
    public $content;
    public $children = array();
    public $unitClass = 'grid';

    public function __construct ($id, $columns = null, $attr = array())
    {
        $this->id = $id;
        $this->columns = $columns;
        $this->attr = $attr;
        $this->content = new Digitalus_Interface_Grid_ContentWrapper($id);
    }
    
    public function setContent($content)
    {
    	$this->content->content = $content;
    }
    
    public function getElement($id)
    {
    	if($this->id == $id) {
    		return $this;
    	}else{
    		if(count($this->children) > 0) {
    			foreach($this->children as $child) {
    				$result = $child->getElement($id);
    				if(is_object($result)) {
    					return $result;
    				}
    			}
    		}
    	}
    	return false;
    }

    public function addElement ($id, $columns = null, $attr = array())
    {
        $element = new Digitalus_Interface_Grid_Element($id, $columns, $attr);
        $this->children[] = $element;
        return $element;
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->_attribs)) {
            $this->attr[$key] = $value;
        }

        return $this;
    }

    public function render ()
    {
        $this->loadView();
        $content = $this->content->render();
        if (count($this->children) > 0) {
            foreach ($this->children as $child) {
                $content .= $child->render();
            }
        }
        $class = $this->makeClass();
        if (!empty($class)) {
            $classString = "class='{$class}'";
        } else {
            $classString = null;
        }
        $xhtml  = "<div id='{$this->id}' {$classString}>" . PHP_EOL;
        $xhtml .= $content . PHP_EOL;
        $xhtml .= '</div>' . PHP_EOL;
        if ($this->getAttribute(self::CLEAR)) {
            $xhtml .= '<div class="clear"></div>';
        }
        return $xhtml;
    }

    public function makeClass ($clearfix = false)
    {
        $class = array();

        if (null !== $this->columns) {
            $class[] = $this->unitClass . '_' . $this->columns;
        }

        $first = $this->getAttribute(self::FIRST);
        $last = $this->getAttribute(self::LAST);
        if ($first == true) {
            $class[] = 'alpha';
        } elseif ($last == true) {
            $class[] = 'omega';
            //automatically add the clear if last is true
            $this->setAttribute(self::CLEAR, true);
        }
        
        $elementClass = $this->getAttribute('class');
        if($elementClass) {
            $class[] = $elementClass;
        }

        $before = $this->getAttribute(self::BEFORE);
        $after  = $this->getAttribute(self::AFTER);
        if ($before > 0) {
            $class[] = 'prefix_' . $this->getAttribute(self::BEFORE);
        }
        if ($after > 0) {
            $class[] = 'suffix_' . $this->getAttribute(self::AFTER);
        }
        if ($clearfix == true) {
            $class[] = 'clearfix';
        }
        return implode(' ', $class);
    }

    public function getAttribute($key)
    {
        if (in_array($key, $this->_attribs) && isset($this->attr[$key])) {
            return $this->attr[$key];
        } else {
            return null;
        }
    }
}
?>