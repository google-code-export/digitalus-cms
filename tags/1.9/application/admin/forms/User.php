<?php
class Admin_Form_User extends Digitalus_Form
{
    public function init()
    {
        // create new element
        $id = $this->createElement('hidden', 'id');
        // element options
        $id->setDecorators(array('ViewHelper'));

        // create new element
        $firstName = $this->createElement('text', 'first_name');
        // element options
        $firstName->setLabel($this->getView()->getTranslation('First Name'))
                  ->setRequired(true)
                  ->setAttrib('size', 40);

        // create new element
        $lastName = $this->createElement('text', 'last_name');
        // element options
        $lastName->setLabel($this->getView()->getTranslation('Last Name'))
                 ->setRequired(true)
                 ->setAttrib('size', 40);

        // create new element
        $email = $this->createElement('text', 'email');
        // element options
        $email->setLabel($this->getView()->getTranslation('Email Address'))
              ->setRequired(true)
              ->setAttrib('size', 50)
              ->addValidator('EmailAddress');

        // create new element
        $adminRole = $this->createElement('select', 'role');
        // element options
        $adminRole->setLabel($this->getView()->getTranslation('Admin Role'));
        $adminRole->addMultiOptions(array(
            'admin'      => $this->getView()->getTranslation('Site Administrator'),
            'superadmin' => $this->getView()->getTranslation('Super Administrator')
        ));

        $updatePassword = $this->createElement('checkbox', 'update_password');
        $updatePassword->setLabel($this->getView()->getTranslation('Update Password?'));

        // create new element
        $password = $this->createElement('password', 'password');
        // element options
        $password->setLabel($this->getView()->getTranslation('Password'));
        $password->setRequired(true);

        // create new element
        $passwordConfirm = $this->createElement('password', 'password_confirm');
        // element options
        $passwordConfirm->setLabel($this->getView()->getTranslation('Confirm Password'))
                        ->addValidator(new Digitalus_Validate_IdenticalField('password'))
                        ->setRequired(true);

        $submit = $this->createElement('submit', 'submit');
        $submit->setLabel($this->getView()->getTranslation('Submit'));

        // add the elements to the form
        $this->addElement($id)
             ->addElement($firstName)
             ->addElement($lastName)
             ->addElement($email)
             ->addElement($adminRole)
             ->addElement($updatePassword)
             ->addElement($password)
             ->addElement($passwordConfirm)
             ->addElement($submit);
    }
}