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
 * Admin Login Form
 *
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Admin
 * @version     $Id:$
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