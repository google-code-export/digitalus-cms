<?php
class Digitalus_Form_Element_Fckeditor extends Zend_Form_Element_Textarea
{
    public function init()
    {
        $this->setAttrib('class', 'fckeditor');
        $this->setDecorators(array('Composite'));
    }
}