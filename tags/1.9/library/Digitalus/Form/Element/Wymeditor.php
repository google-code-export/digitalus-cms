<?php
class Digitalus_Form_Element_Wymeditor extends Zend_Form_Element_Textarea
{
    public function init()
    {
        $this->setAttrib('class', 'wymeditor');
        $this->setDecorators(array('Composite'));
    }
}