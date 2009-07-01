<?php

class Digitalus_Layout_Grid {
    protected $_container = null;

    /**
     * this class wraps a grid960 container.
     * you create the container in the constructor
     * then add units to it at will.
     *
     * @param int $containerWidth
     */
    public function __construct($columns, $before = 0, $after = 0)
    {

        $this->_container = new SimpleXMLElement();
    }

    public function startRow()
    {

    }

    public function addUnit()
    {

    }

    public function endRow()
    {

    }

    public function render()
    {
        return $this->_container->asXml();
    }

    protected function _addUnit($cols, $before = 0, $after = 0)
    {
        return $this->_container;
    }

    protected function getClass()
    {

    }

}

?>