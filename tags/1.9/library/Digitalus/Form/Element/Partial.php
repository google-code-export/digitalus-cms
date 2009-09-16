<?php

require_once ('Zend/Form/Element.php');

class Digitalus_Form_Element_Partial extends Digitalus_Form_Element_Xml
{
    public $partial;

    public function init()
    {
        $this->setDecorators(array(array("ViewScript", array(
            'viewScript' => $this->partial,
            'class'      => 'partial'
        ))));
    }

    public function setPartial($script)
    {
        $this->partial = $script;
    }
}