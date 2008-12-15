<?php
abstract class DSF_Content_Form_Abstract
{
	const PAGE_ACTION = '/admin/page/edit';
	public $form;
	public $decorators = array(
		'Errors',
	    'Composite',
		array('HtmlTag', array('tag' => 'div')),
		array('Label', array('tag' => 'label')),
	);
	
	public function __construct()
	{
		$this->form = new Zend_Form();
		$front = Zend_Controller_Front::getInstance();
		$this->form->setAction($front->getBaseUrl() . self::PAGE_ACTION )
			->setMethod('post');
		$this->form->addElementPrefixPath('DSF_Decorator', 'DSF/Form/Decorator', 'decorator');
		$this->form->addPrefixPath('DSF_Form_Element', 'DSF/Form/Element/', 'element');
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

		$version = $this->form->createElement('hidden','version');
			 
		 $this->form->addElement($page_id)
		 	->addElement($name)
		 	->addElement($version);
	}
	
	public function setDecorators()
	{
		$this->form->setDecorators($this->decorators);
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