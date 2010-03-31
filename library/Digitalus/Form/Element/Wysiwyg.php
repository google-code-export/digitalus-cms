<?php
class Digitalus_Form_Element_Wysiwyg extends Zend_Form_Element_Textarea
{
    public function init()
    {
        parent::init();
        $this->setAttrib('id', 'wysiwyg');
        $this->setAttrib('class', 'wysiwyg');
        $this->setDecorators(array('Composite'));
        $this->addFilter('StripSlashes');
    }
}