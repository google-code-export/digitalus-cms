<?php
/**
 * Digitalus CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://digitalus-media.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@digitalus-media.com so we can send you a copy immediately.
 *
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id:$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.9.0
 */

/**
 * @see Digitalus_Form
 */
require_once 'Digitalus/Form.php';

/**
 * Admin User Form
 *
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Admin
 * @version     $Id:$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.9.0
 */
class Admin_Form_User extends Digitalus_Form
{
    /**
     * Initialize the form
     *
     * @return void
     */
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
              ->addValidator('EmailAddress')
              ->setErrorMessages(array($this->getView()->getTranslation('A valid email address is required')));

        // create new element
        $openid = $this->createElement('text', 'openid');
        // element options
        $openid->setLabel($this->getView()->getTranslation('OpenID'))
               ->setAttrib('size', 50);

        // create new element
        $adminRole = $this->createElement('select', 'role');
        // element options
        $adminRole->setLabel($this->getView()->getTranslation('Admin Role'));
        $adminRole->addMultiOptions(array(
            'admin'      => $this->getView()->getTranslation('Site Administrator'),
            'superadmin' => $this->getView()->getTranslation('Super Administrator')
        ));

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
             ->addElement($openid)
             ->addElement($adminRole)
             ->addElement($password)
             ->addElement($passwordConfirm)
             ->addElement($submit)
             ->addDisplayGroup(array('form_instance', 'id', 'first_name', 'last_name',
                                     'email', 'openid', 'role', 'update_password',
                                     'update_password', 'password', 'password_confirm', 'submit'),
                                     'admin_form',
                                     array('legend' => $this->getView()->getTranslation('Account Information')));

        $this->setDecorators(array(
            'FormElements',
            'Form',
        ));

        $this->setDisplayGroupDecorators(array(
            'FormElements',
            'Fieldset',
        ));
    }
}