<?php
class Digitalus_Form_Element_Wysiwyg extends Zend_Form_Element_Textarea
{
    public function init()
    {
        parent::init();
        $this->setAttribs(array('id' => 'wysiwyg', 'class' => 'wysiwyg'))
             ->setDecorators(array('Composite'))
             ->addFilter('StripSlashes');
    }
}