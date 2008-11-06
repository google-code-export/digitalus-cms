<?php
abstract class DSF_Content_Form_Abstract
{
	const PAGE_ACTION = '/admin/page/edit';
	public $form;
	public $decorators = array(
		'Errors',
		array('HtmlTag', array('tag' => 'dd')),
		array('Label', array('tag' => 'dt')),
	);
	
	public function __construct()
	{
		$this->form = new Zend_Form();
		$this->form->setAction(self::PAGE_ACTION )
			->setMethod('post');
		$this->addBase();
		$this->setup();
		//$this->setDecorators();
	}
	 //adds the base fields to the form
	protected function addBase()
	{
		$name = $this->form->createElement('text','name');
		$name->setRequired(true)
			 ->setLabel('Page Name:');	

		$page_id = $this->form->createElement('hidden','page_id');
		$page_id->setRequired(true);
			 
		 $this->form->addElement($page_id)
		 	->addElement($name);
	}
	
	public function setDecorators()
	{
		$this->form->addDecorators($this->decorators);
	}
	
	public function getValues()
	{
		if($this->form->isValid($_POST)) {
			return $this->form->getValues();
		}
	}
	
	public function getErrors()
	{
		if(!$this->form->isValid($_POST)) {
			return $this->form->getErrors();
		}
	}
	
	public function setValues($values)
	{
		$this->form->populate($values);
	}
	
	public function render()
	{
		return $this->form->render();
	}
}