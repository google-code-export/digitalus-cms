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
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
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
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Admin
 * @version     $Id:$
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
        $view = $this->getView();

        // create new element
        $id = $this->createElement('hidden', 'id', array(
            'decorators'    => array('ViewHelper')
        ));

        // create new element
        $userName = $this->createElement('text', 'username', array(
            'label'         => $view->getTranslation('Username'),
            'required'      => true,
            'filters'       => array('StringTrim'),
            'validators'    => array(
                array('NotEmpty', true),
                array('StringLength', true, array(4, 20)),
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
            'attribs'       => array('size' => 50),
            'errorMessages' => array('Please provide a valid OpenId!'),
        ));

        // create new element
        $active = $this->createElement('checkbox', 'active', array(
            'label'         => $view->getTranslation('Activated'),
        ));

        // create new element
        $adminRole = $this->createElement('select', 'role', array(
            'label'         => $view->getTranslation('Admin Role'),
            'multiOptions'  => array(
                'admin'      => $view->getTranslation('Site Administrator'),
                'superadmin' => $view->getTranslation('Super Administrator')
            ),
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
            'label'         => $view->getTranslation("Please verify you're a human"),
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
        $this->addElement($id)
             ->addElement($userName)
             ->addElement($firstName)
             ->addElement($lastName)
             ->addElement($email)
             ->addElement($openid)
             ->addElement($active)
             ->addElement($adminRole)
             ->addElement($password)
             ->addElement($passwordConfirm)
             ->addElement($captcha)
             ->addElement($submit)
             ->addDisplayGroup(array('form_instance', 'id', 'username', 'first_name', 'last_name',
                                     'email', 'openid', 'active', 'role',
                                     'password', 'password_confirm', 'captcha', 'submitAdminUserForm'),
                                     'admin_form',
                                     array('legend' => $view->getTranslation('Account Information')));

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