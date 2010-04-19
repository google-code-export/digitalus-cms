<?php
class Digitalus_Form_Element_Tinymce extends Zend_Form_Element_Textarea
{
    public function init()
    {
        parent::init();
        $this->setAttrib('class', 'tinymce')
             ->setDecorators(array('Composite'))
             ->addFilter('StripSlashes');
    }
}