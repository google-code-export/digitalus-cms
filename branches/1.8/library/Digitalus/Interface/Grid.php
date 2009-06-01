<?php

class Digitalus_Interface_Grid extends Digitalus_Interface_Grid_Abstract {
    public $containerClass = 'container';
    const PATH_TO_GRIDS = './application/public/views/grids';
    public $id;
    public $columns;
    public $grid = null;
    public $view;
    private $_styleSheets = array(
        'styles/grid-960/styles/text.css',
        'styles/grid-960/styles/960.css',
        'styles/grid-960/styles/reset.css'
    );

    public function __construct($id = null, $columns = null, $attr = array())
    {
    	if($id != null) {
            $this->id = $id;
    	}
    	if($columns != null) {
            $this->columns = $columns;
    	}
    	
        $this->_loadStyles();
        $grid = new Digitalus_Interface_Grid_Element($id . '_wrapper');
        $this->grid = $grid;
        $this->init();
        
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        if (null === $viewRenderer->view) {
            $viewRenderer->initView();
        }
        $this->view = $viewRenderer->view;        
    }
    
    static function load($grid, $id)
    {
    	require_once self::PATH_TO_GRIDS . '/' . $grid . '.php';
    	return new $grid($id);
    }
    
    public function getElement($id)
    {
    	return $this->grid->getElement($id);
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

    private function _loadStyles()
    {
        $this->loadView();
        $front = Zend_Controller_Front::getInstance();
        $baseUrl = $front->getBaseUrl();
        foreach ($this->_styleSheets as $styleSheet) {
            $this->view->headLink()->prependStylesheet($baseUrl . '/' . $styleSheet);
        }
    }
    
    
}
?>