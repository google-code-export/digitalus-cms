<?php
class Digitalus_Form_Element_Markitup extends Zend_Form_Element_Textarea
{
    public function init()
    {
        parent::init();
        $this->setAttrib('class', 'markItUp');
        $this->setDecorators(array('Composite'));
        $this->addFilter('StripSlashes');
    }
}