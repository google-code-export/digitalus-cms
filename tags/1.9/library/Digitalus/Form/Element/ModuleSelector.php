<?php
require_once ('Digitalus/Form/Element/Xml.php');

class Digitalus_Form_Element_ModuleSelector extends Digitalus_Form_Element_Xml
{
    public function init()
    {
        $this->setDecorators(array(array('ViewScript', array(
            'viewScript' => 'module/partials/load-module.phtml',
            'class'      => 'partial'
        ))));
    }
}