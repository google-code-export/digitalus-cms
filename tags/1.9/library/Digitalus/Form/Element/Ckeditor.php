<?php
class Digitalus_Form_Element_Ckeditor extends Zend_Form_Element_Textarea
{
    public function init()
    {
        $this->setAttrib('class', 'ckeditor');
        $this->setDecorators(array('Composite'));
    }
}
?>