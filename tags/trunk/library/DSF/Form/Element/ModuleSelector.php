<?php

require_once ('Zend/Form/Element.php');

class DSF_Form_Element_ModuleSelector extends DSF_Form_Element_Xml  {

    public function init()
    {
        $this->setDecorators(array(array("ViewScript", array(
            'viewScript'    => 'module/partials/load-module.phtml',
            'class'    =>    'partial'
        ))));
    }
}

?>