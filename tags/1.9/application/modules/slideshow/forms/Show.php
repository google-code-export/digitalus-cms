<?php
class Show_Form extends Digitalus_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setMethod('post');

        $id = $this->createElement('hidden', 'id');
        $id->removeDecorator('Label');


        $name = $this->createElement('text', 'name');
        $name->setAttrib('size', 40)
             ->setRequired('true')
             ->setLabel($this->getView()->getTranslation('Slideshow Name') . ':');

        // create new element
        $description = $this->createElement('textarea', 'description');
        // element options
        $description->setLabel($this->getView()->getTranslation('Description'))
                    ->setRequired(false)
                    ->setAttrib('cols', 40)
                    ->setAttrib('rows', 8);

        $submit = $this->createElement('submit', 'submit');

        // add the elements to the form
        $this->addElement($id)
             ->addElement($name)
             ->addElement($description)
             ->addElement($submit);
    }
}
?>