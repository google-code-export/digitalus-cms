<?php
class Digitalus_Form_Element_Fckeditor extends Zend_Form_Element_Textarea
{
    public function init()
    {
        $form = new Digitalus_Form();

        parent::init();
        $this->setAttrib('class', 'fckeditor')
             ->setDecorators(array('Composite'))
             ->addFilter('StripSlashes');
    }
}