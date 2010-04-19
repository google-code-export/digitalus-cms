<?php
require_once ('Digitalus/Form/Element/Xml.php');

class Digitalus_Form_Element_ModuleSelector extends Digitalus_Form_Element_Xml
{
    /**
     * Use formSelect view helper by default
     * @var string
     */
    public $helper = 'formSelect';

    public function init()
    {
        parent::init();
        $this->setDecorators(array(
            array('ViewScript', array(
                'viewScript' => 'module/partials/load-module.phtml',
                'class'      => 'partial'
            ))
        ));
    }
}