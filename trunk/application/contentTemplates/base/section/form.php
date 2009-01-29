<?php
class Base_Section_Form extends DSF_Content_Form_Abstract {
    public function setup() {
    	$headline = $this->form->createElement('text','headline');
    	$headline->setLabel("Headline:");
    	
        $tagline = $this->form->createElement('textarea','tagline');
        $tagline->setLabel('Tagline:')
            ->setAttrib('class', "med_short");
        
        $content = $this->form->createElement( 'textarea', 'content' );
    
        $content->setRequired(true)
            ->setLabel('Content:')
            ->setDecorators(array('Composite'))
            ->setAttrib('class',"editor wiki");
        
        // Add elements to form:
        $this->form->addElement($headline)
        	->addElement($tagline)
            ->addElement($content)
            ->addElement('submit','update',array('label'=>'Update Page'));
    }
}