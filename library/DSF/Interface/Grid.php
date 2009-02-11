<?php

class DSF_Interface_Grid extends DSF_Interface_Grid_Abstract {
    public $containerClass = 'container';
    public $id;
    public $columns;
    public $grid = null;
    private $_styleSheets = array(
        'styles/grid-960/styles/text.css',
        'styles/grid-960/styles/960.css',
        'styles/grid-960/styles/reset.css'
    );

    public function __construct($id, $columns, $attr = array()) {
        $this->id = $id;
        $this->columns = $columns;
        $this->_loadStyles();
        $grid = new DSF_Interface_Grid_Element('wrapper', $columns, $attr);
        $this->grid = $grid;
    }
    
    public function addElement($id, $columns, $attr = array())
    {
        return $this->grid->addElement($id, $columns, $attr);
    }

    public function render()
    {
        $xhtml = "<div id='{$this->id}' class='{$this->containerClass}_{$this->columns}'>";
        $xhtml .= $this->grid->render();
        $xhtml .= "</div>" . PHP_EOL;
        return $xhtml;
    }
    
    private function _loadStyles() {
        $this->loadView();
        $front = Zend_Controller_Front::getInstance();
        $baseUrl = $front->getBaseUrl();
        foreach ($this->_styleSheets as $styleSheet) {
            $this->view->headLink()->prependStylesheet($baseUrl . '/' . $styleSheet);
        }
    }
}

?>