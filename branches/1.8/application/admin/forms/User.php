<?php
class Admin_Form_User extends Digitalus_Form
{
    public function init()
    {
        // create new element
        $id = $this->createElement('hidden', 'id');
        // element options
        $id->setDecorators(array('ViewHelper'));
        // add the element to the form
        $this->addElement($id);

        // create new element
        $firstName = $this->createElement('text', 'first_name');
        // element options
        $firstName->setLabel($this->getView()->getTranslation('First Name'))
                  ->setRequired(true)
                  ->setAttrib('size',40);
        // add the element to the form
        $this->addElement($firstName);

        // create new element
        $lastName = $this->createElement('text', 'last_name');
        // element options
        $lastName->setLabel($this->getView()->getTranslation('Last Name'))
                 ->setRequired(true)
                 ->setAttrib('size',40);
        // add the element to the form
        $this->addElement($lastName);

        // create new element
        $email = $this->createElement('text', 'email');
        // element options
        $email->setLabel($this->getView()->getTranslation('Email Address'))
              ->setRequired(true)
              ->setAttrib('size',50)
              ->addValidator('EmailAddress');
        // add the element to the form
        $this->addElement($email);

        // create new element
        $adminRole = $this->createElement('select', 'role');
        // element options
        $adminRole->setLabel('Admin Role');
        $adminRole->addMultiOptions(array(
            'admin'      => $this->getView()->getTranslation('Site Administrator'),
            'superadmin' => $this->getView()->getTranslation('Super Administrator')
        ));

        // add the element to the form
        $this->addElement($adminRole);

        $updatePassword = $this->createElement('checkbox','update_password');
        $updatePassword->setLabel($this->getView()->getTranslation('Update Password?'));
        $this->addElement($updatePassword);


        // create new element
        $password = $this->createElement('password', 'password');
        // element options
        $password->setLabel($this->getView()->getTranslation('Password'));
        $password->setRequired(true);
        // add the element to the form
        $this->addElement($password);

        // create new element
        $passwordConfirm = $this->createElement('password', 'password_confirm');
        // element options
        $passwordConfirm->setLabel($this->getView()->getTranslation('Confirm Password'))
                        ->addValidator(new Digitalus_Validate_IdenticalField('password'))
                        ->setRequired(true);

        // add the element to the form
        $this->addElement($passwordConfirm);
        $submit = $this->addElement('submit', 'submit', array('label' => $this->getView()->getTranslation('Submit')));
    }
}
?>