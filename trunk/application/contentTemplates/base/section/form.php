<?php
class Base_Section_Form extends DSF_Content_Form_Abstract {
    public function setup() {
        $tagline = $this->form->createElement('textarea','tagline');
        $tagline->setLabel('tagline:')
            ->setAttrib('class', "med_short");
        
        $content = $this->form->createElement( 'textarea', 'content' );
    
        $content->setRequired(true)
            ->setLabel('Content')
            ->setDecorators(array('Composite'))
            ->setAttrib('class',"editor html");
        
        // Add elements to form:
        $this->form->addElement($tagline)
            ->addElement($content)
            ->addElement('submit','update',array('label'=>'Update Page'));
    }
}