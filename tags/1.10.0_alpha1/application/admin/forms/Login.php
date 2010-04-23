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
 * @version     $Id: Login.php 701 2010-03-05 16:23:59Z lowtower@gmx.de $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.9.0
 */

/**
 * @see Digitalus_Form
 */
require_once 'Digitalus/Form.php';

/**
 * Admin Login Form
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Admin
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.9.0
 */
class Admin_Form_Login extends Digitalus_Form
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
        $id = $this->createElement('hidden', 'id', array(
            'decorators'    => array('ViewHelper')
        ));

        // create new element
        $userName = $this->createElement('text', 'adminUsername', array(
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
            'attribs'       => array('size' => 50),
            'errorMessages' => array('You must enter a valid username.'),
        ));

        // create new element
        $password = $this->createElement('password', 'adminPassword', array(
            'label'         => $view->getTranslation('Password'),
            'required'      => true,
            'filters'       => array('StringTrim'),
            'errorMessages' => array('You must enter your password.'),
        ));

        $submit = $this->createElement('submit', 'submitAdminLogin', array(
            'label'         => $view->getTranslation('Login'),
            'attribs'       => array('class' => 'submit'),
        ));

        // add the elements to the form
        $this->addElement($id)
             ->addElement($userName)
             ->addElement($password)
             ->addElement($submit)
             ->addDisplayGroup(
                 array('form_instance', 'id', 'adminUsername', 'adminPassword', 'submitAdminLogin'),
                 'adminLoginGroup',
                 array('legend' => $view->getTranslation('Standard Login'))
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