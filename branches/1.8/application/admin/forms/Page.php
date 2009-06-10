<?php
class Admin_Form_Page extends Digitalus_Form 
{
	public function init()
	{
		$id = $this->createElement('hidden', 'id');
		$id->setDecorators(array('ViewHelper'));
		$this->addElement($id);
		
		$name = $this->createElement('text', 'page_name');
		$name->addFilter('StripTags');
		$name->setRequired(TRUE);
		$name->setLabel('Page Name: ');
		$name->setAttrib('size', 50);
		$name->setOrder(0);
		$this->addElement($name);
		
		$parentId = $this->createElement('select', 'parent_id');
        $parentId->setLabel($this->getView()->getTranslation('Parent page: '));
	    $mdlIndex = new Model_Page();
        $index = $mdlIndex->getIndex(0, 'name');
        $parentId->addMultiOption(0, $this->getView()->getTranslation('Site Root'));
        if (is_array($index)) {
            foreach ($index as $id => $page) {
                $parentId->addMultiOption($id, $page);
            }
        }	
        $parentId->setOrder(1);	
        $this->addElement($parentId);
        
        $design = $this->createElement('select','design_id');
        $design->setLabel($this->getView()->getTranslation('Page design: '));
        $design->addMultiOption(0, $this->getView()->getTranslation('Use default'));
        $mdlDesign = new Model_Design();
        $designs = $mdlDesign->listDesigns();
        if ($designs) {
            foreach ($designs as $d) {
                $design->addMultiOption($d->id, $d->name);
            }
        }
        $design->setOrder(2);
        $this->addElement($design);
                
        $continue = $this->createElement('checkbox', 'continue_adding_pages');
        $continue->setLabel($this->getView()->getTranslation('Continue adding pages') . '?');
        $continue->setOrder(3);
        $this->addElement($continue);
        
        $submit = $this->createElement('submit', 'submit');
        $submit->setOrder(1000);
        $this->addElement($submit);
	}
}
?>