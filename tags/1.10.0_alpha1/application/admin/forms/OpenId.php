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
 * @version     $Id: OpenId.php 701 2010-03-05 16:23:59Z lowtower@gmx.de $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.9.0
 */

/**
 * @see Digitalus_Form
 */
require_once 'Digitalus/Form.php';

/**
 * Admin OpenID Form
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
class Admin_Form_OpenId extends Digitalus_Form
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
        $openid = $this->createElement('text', 'openid_identifier', array(
            'label'         => $view->getTranslation('OpenID'),
            'required'      => true,
            'filters'       => array('StringTrim'),
// @TODO: add validator for openids
            'attribs'       => array('size'  => 50,
                                     'class' => 'openid_login'),
            'errorMessages' => array('You must enter a valid OpenID.'),
        ));

        $submit = $this->createElement('submit', 'openid_action', array(
            'label'         => $view->getTranslation('Login'),
            'attribs'       => array('class' => 'submit'),
        ));

        // add the elements to the form
        $this->addElement($id)
             ->addElement($openid)
             ->addElement($submit)
             ->addDisplayGroup(array('form_instance', 'id', 'openid_identifier', 'openid_action'),
                                     'adminOpenIdGroup',
                                     array('legend' => $view->getTranslation('OpenID Login'))
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