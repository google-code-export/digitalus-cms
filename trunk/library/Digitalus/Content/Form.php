<?php
class Digitalus_Content_Form extends Digitalus_Form
{
    const PAGE_ACTION = '/admin/page/edit';

    public function init()
    {
        $front = Zend_Controller_Front::getInstance();

        $this->setAction($front->getBaseUrl() . self::PAGE_ACTION)
             ->setMethod('post');
        $this->addElementPrefixPath('Digitalus_Decorator', 'Digitalus/Form/Decorator', 'decorator');
        $this->addPrefixPath('Digitalus_Form_Element', 'Digitalus/Form/Element/', 'element');

        $name = $this->createElement('text', 'name');
        $name->setRequired(true)
             ->setLabel($this->getView()->getTranslation('Page Name'));

        $page_id = $this->createElement('hidden', 'id');
        $page_id->setDecorators(array('ViewHelper'));
        $page_id->setRequired(true);

        $version = $this->createElement('hidden', 'version');
        $version->setDecorators(array('ViewHelper'));

        $submit = $this->createElement('submit', 'submit');
        $submit->setLabel($this->getView()->getTranslation('Save Changes'));
        $submit->setDecorators(array('ViewHelper'));
        $submit->setOrder(1000); // i would assume this is the end

        $this->addElement($page_id)
             ->addElement($name)
             ->addElement($version)
             ->addElement($submit);

    }

    public function loadFromTemplate($template)
    {
        $control = new Digitalus_Content_Control($this);
        $control->registerControlsFromTemplate($template);
    }
}