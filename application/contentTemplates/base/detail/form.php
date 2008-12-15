<?php
class Base_Detail_Form extends DSF_Content_Form_Abstract 
{
	public function setup()
	{		
		$teaser = $this->form->createElement('textarea','abstract');
		$teaser->setLabel('Teaser:')
			->setAttrib('class',"med_short");
		
		$content = $this->form->createElement('textarea', 'content');
		$content->setRequired(true)
			->setLabel("Content:")
			->setAttrib('class',"med_tall");
		
		// Add elements to form:
		$this->form->addElement($teaser)
			->addElement($content)
			->addElement('submit', 'update', array('label' => 'Update Page'));
	}
	
	public function setValues($values)
	{
		$values['name'] = "This actually works";
	    $this->form->populate($values);
	}
	
	public function getValues()
	{
	    $values = parent::getValues();
	    Zend_Debug::dump($values);
	    die();
	}
}