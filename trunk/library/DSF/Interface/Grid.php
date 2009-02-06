<?php

class DSF_Interface_Grid {
    const TYPE_START = 'start';
    const TYPE_MIDDLE = 'middle';
    const TYPE_END = 'end';
    public $container = null;
    public $containerClass = 'container';
    public $unitClass = 'grid';
    public $gridColumns = null;
    public $view;
    private $_styleSheets = array(
        'styles/grid-960/styles/reset.css',
        'styles/grid-960/styles/text.css',
        'styles/grid-960/styles/960.css'
    );

    public function __construct($columns, $before = 0, $after = 0) {
        $this->loadView();
        $this->_loadStyles();

        $this->gridColumns = $columns;
        $div = "<div />";
        $this->container = simplexml_load_string($div);
        $class = $this->makeClass($this->gridColumns,$this->containerClass, $before, $after, false, false);
        $this->container->addAttribute('class', $class);
        $this->container->addAttribute('id', 'wrapper');
    }

    public function addElement($id, $columns, $parent = null, $type=null, $before = 0, $after = 0)
    {
        if ($type == null) {
            $type = self::TYPE_MIDDLE;
        }
        if (!$parent) {
            // Add Wrapper
            $parentObject = $this->container->addChild('div','');
            $parentObject->addAttribute('id', $id.'_wrapper');
            $parentObject->addAttribute('class', 'clearfix');
        } else {
            // No wrapper, already nested
            $parentObject = $parent->children();
        }
        //Configure column type
        switch ($type) {
            case self::TYPE_START:
                $alphaFlag = true;
                $omegaFlag = false;
                break;
            case self::TYPE_END:
                $alphaFlag = false;
                $omegaFlag = true;
                break;
            default:
                $alphaFlag = false;
                $omegaFlag = false;
                break;
        }
        //Create
        $this->_addUnit($id, $columns, $parentObject, $before, $after, $alphaFlag, $omegaFlag);
        // Add clear after ending a row
        if ($omegaFlag || $columns == $this->gridColumns) {
            $this->_clear($parentObject);
        }
        return $parentObject;
    }

    public function populate($element, $content, $wrapper = "div")
    {
        $element->addChild($wrapper, $content);
    }

    protected function _addUnit($id, $columns, $parent = null, $before = 0, $after = 0, $first = false, $last = false)
    {
        $div = $parent->addChild('div','');
        $class = $this->makeClass($columns, $this->unitClass, $before, $after, $first, $last);

        //load the content from the placeholder
        $placeholderKey = $id . '_content';
        $content = $this->view->placeholder($placeholderKey)->toString();

        //Only need a nested container if there is content there
        if (!empty($content)) {
            $innerContent = $div->addChild('div', $content);
            $innerContent->addAttribute('class', 'innerContent '.$id.'_inner');
        }

        $div->addAttribute('class', $class);
        $div->addAttribute('id', $id);
        return $div;
    }

    protected function _clear($parent = null)
    {
        $clear = $parent->addChild('div','');
        $clear->addAttribute('class', 'clearfix');
    }

    public function render()
    {
        // TODO: this will decode things that are meant to be encoded
        return html_entity_decode($this->container->asXml());
    }

    public function makeClass($columns, $type = null, $before = 0, $after = 0, $first = false, $last = false)
    {
        $class = array();

        if ($type != null) {
            $baseClass = $type;
        } else {
            $baseClass = $this->unitClass;
        }

        $class[] = $baseClass . '_' . $columns;

        if ($first == true) {
            $class[] = "alpha";
        } elseif ($last == true) {
            $class[] = "omega";
        }

        if ($before > 0) {
            $class[] = "prefix_" . $before;
        }

        if ($after > 0) {
            $class[] = "suffix_" . $after;
        }

        return implode(' ', $class);

    }

    public function loadView() {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        if (null === $viewRenderer->view) {
            $viewRenderer->initView();
        }
        $view = $viewRenderer->view;
        $this->view = $view;
    }

    private function _loadStyles() {
        $front = Zend_Controller_Front::getInstance();
        $baseUrl = $front->getBaseUrl();
        foreach ($this->_styleSheets as $styleSheet) {
            $this->view->headLink()->prependStylesheet($baseUrl . '/' . $styleSheet);
        }
    }
}

?>