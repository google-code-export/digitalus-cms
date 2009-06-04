<?php
class Blog_Form extends Zend_Form
{
    public function __construct($options = null) {
        parent::__construct($options);

        $id = $this->createElement('hidden', 'id');

        $name = $this->createElement('text', 'name');
        $name->setAttrib('size',40);
        $name->setRequired('true');
        $name->setLabel('Blog Name: ');

        $submit = $this->createElement('submit', 'submit');

        $this->setMethod('post')
             ->addElement($id)
             ->addElement($name)
             ->addElement($submit);
    }
}
?>