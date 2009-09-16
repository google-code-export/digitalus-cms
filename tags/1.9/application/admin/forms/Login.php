<?php
class Admin_Form_Login extends Digitalus_Form
{
    public function init()
    {
        $this->setAction($this->getView()->getBaseUrl() . '/admin/auth/login')
             ->setMethod('post');

        // create new element
        $id = $this->createElement('hidden', 'id');
        // element options
        $id->setDecorators(array('ViewHelper'));

        // create new element
        $username = $this->createElement('text', 'adminUsername');
        // element options
        $username->setLabel($this->getView()->getTranslation('Username'))
              ->setRequired(true)
              ->setAttrib('size', 50)
              ->addValidator('EmailAddress')
              ->setErrorMessages(array($this->getView()->getTranslation('You must enter a username.')));

        // create new element
        $password = $this->createElement('password', 'adminPassword');
        // element options
        $password->setLabel($this->getView()->getTranslation('Password'))
                 ->setRequired(true)
                 ->setErrorMessages(array($this->getView()->getTranslation('You must enter a password.')));

        $submit = $this->createElement('submit', 'submit');
        $submit->setLabel($this->getView()->getTranslation('Submit'));

        // add the elements to the form
        $this->addElement($id)
             ->addElement($username)
             ->addElement($password)
             ->addElement($submit);
    }
}