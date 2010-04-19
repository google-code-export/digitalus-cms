<?php
class Digitalus_Form_Element_Ckeditor extends Zend_Form_Element_Textarea
{
    public function init()
    {
        parent::init();
        $this->setAttrib('class', 'ckeditor')
             ->setDecorators(array('Composite'))
             ->addFilter('StripSlashes');
    }
}