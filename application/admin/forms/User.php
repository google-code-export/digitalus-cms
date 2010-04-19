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
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: User.php 701 2010-03-05 16:23:59Z lowtower@gmx.de $
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
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Admin
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.9.0
 * @uses        Model_User
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
        parent::init();

        $view = $this->getView();

        // create new element
#        $id = $this->createElement('hidden', 'id', array(
#            'decorators'    => array('ViewHelper')
#        ));

        // create new element
        $userName = $this->createElement('text', 'name', array(
            'label'         => $view->getTranslation('Username'),
            'required'      => true,
            'filters'       => array('StringTrim'),
            'validators'    => array(
                array('NotEmpty', true),
                array('StringLength', true, array(4, Model_User::USERNAME_LENGTH)),
                array('UsernameExists'),
                array('Regex', true, array(
                    'pattern'  => Model_User::USERNAME_REGEX,
                    'messages' => array('regexNotMatch' => Model_User::USERNAME_REGEX_NOTMATCH),
                )),
            ),
            'attribs'       => array('size' => 40),
        ));

        // create new element
        $firstName = $this->createElement('text', 'first_name', array(
            'label'         => $view->getTranslation('First name'),
            'required'      => true,
            'filters'       => array('StringTrim'),
            'validators'    => array(
                array('NotEmpty', true),
                array('StringLength', true, array(4, 40)),
                array('Regex', true, array(
                    'pattern'  => Model_User::USERNAME_REGEX,
                    'messages' => array('regexNotMatch' => Model_User::USERNAME_REGEX_NOTMATCH),
                )),
            ),
            'attribs'       => array('size' => 40),
        ));

        // create new element
        $lastName = $this->createElement('text', 'last_name', array(
            'label'         => $view->getTranslation('Last name'),
            'required'      => true,
            'filters'       => array('StringTrim'),
            'validators'    => array(
                array('NotEmpty', true),
                array('StringLength', true, array(4, 40)),
                array('Regex', true, array(
                    'pattern'  => Model_User::USERNAME_REGEX,
                    'messages' => array('regexNotMatch' => Model_User::USERNAME_REGEX_NOTMATCH),
                )),
            ),
            'attribs'       => array('size' => 40),
        ));

        // create new element
        $email = $this->createElement('text', 'email', array(
            'label'         => $view->getTranslation('Email address'),
            'required'      => true,
            'filters'       => array('StringTrim'),
            'validators'    => array('EmailAddress'),
            'attribs'       => array('size' => 50),
            'errorMessages' => array('A valid email address is required'),
        ));

        // create new element
        $openid = $this->createElement('text', 'openid', array(
            'label'         => $view->getTranslation('OpenID'),
            'filters'       => array('StringTrim'),
            'validators'    => array('OpenIdExists'),
            'attribs'       => array('size' => 50),
        ));

        // create new element
        $updatePassword = $this->createElement('checkbox', 'update_password', array(
            'label'         => $view->getTranslation('Update Password?'),
            'checked'       => false,
        ));

        // create new element
        $active = $this->createElement('checkbox', 'active', array(
            'label'         => $view->getTranslation('Activated'),
            'checked'       => true,
        ));

        // create new element
        $adminRole = $view->selectGroup('role', null, null, null, 'superadmin', false);
        $adminRole->setOptions(array(
            'label'         => $view->getTranslation('Admin Role'),
        ));

        // create new element
        $password = $this->createElement('password', 'password', array(
            'label'         => $view->getTranslation('Password'),
            'required'      => true,
            'filters'       => array('StringTrim'),
            'attribs'       => array('size' => 50),
        ));

        // create new element
        $passwordConfirm = $this->createElement('password', 'password_confirm', array(
            'label'         => $view->getTranslation('Confirm Password'),
            'required'      => true,
            'filters'       => array('StringTrim'),
            'attribs'       => array('size' => 50),
            'validators'    => array(
                array('IdenticalField', true, 'password'),
            )
        ));

        $captcha = $this->createElement('captcha', 'captcha', array(
            'label'         => $view->getTranslation("Please verify you're a human!") . ':',
            'required'      => true,
            'filters'       => array('StringTrim'),
            'captcha' => array(
                'captcha' => 'Figlet',
                'wordLen' => 6,
                'timeout' => 300,
                'height'  => 100,
                'width'   => 260,
            ),
            'errorMessages' => array('Please type in the correct code from the captcha!'),
        ));

        $submit = $this->createElement('submit', 'submitAdminUserForm', array(
            'label'         => $view->getTranslation('Submit'),
            'attribs'       => array('class' => 'submit'),
        ));

        // add the elements to the form
        $this->addElement($userName)
             ->addElement($firstName)
             ->addElement($lastName)
             ->addElement($email)
             ->addElement($openid)
             ->addElement($updatePassword)
             ->addElement($active)
             ->addElement($adminRole)
             ->addElement($password)
             ->addElement($passwordConfirm)
             ->addElement($captcha)
             ->addElement($submit)
             ->addDisplayGroup(array('form_instance', 'name', 'first_name', 'last_name',
                                     'email', 'openid', 'update_password', 'active', 'role',
                                     'password', 'password_confirm', 'captcha', 'submitAdminUserForm'),
                                     'adminUserGroup',
                                     array('legend' => $view->getTranslation('Account Information'))
             );

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