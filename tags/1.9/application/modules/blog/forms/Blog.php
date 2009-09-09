<?php
class Blog_Form extends Zend_Form
{
    public function __construct($options = null) {
        parent::__construct($options);

        $view = $this->getView();

        $id = $this->createElement('hidden', 'id');

        $name = $this->createElement('text', 'name');
        $name->setAttrib('size', 40)
             ->setRequired('true')
             ->setLabel($view->getTranslation('Blog Name' . ':'));

        $submit = $this->createElement('submit', 'submit');

        $this->setMethod('post')
             ->addElement($id)
             ->addElement($name)
             ->addElement($submit);
    }
}