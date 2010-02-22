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
 * Admin OpenID Form
 *
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Admin
 * @version     $Id:$
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
        $this->setAction($this->getView()->getBaseUrl() . '/admin/auth/openid')
             ->setMethod('post');

        // create new element
        $id = $this->createElement('hidden', 'id');
        // element options
        $id->setDecorators(array('ViewHelper'));

        // create new element
        $openid = $this->createElement('text', 'openid_identifier');
        // element options
        $openid->setLabel($this->getView()->getTranslation('OpenID'))
               ->setRequired(true)
               ->setAttribs(array('size' => 50, 'class' => 'openid_login'))
#               ->addValidator('Hostname', false, Zend_Validate_Hostname::ALLOW_DNS)
               ->setErrorMessages(array($this->getView()->getTranslation('You must enter a valid OpenID.')));

        $submit = $this->createElement('submit', 'openid_action');
        $submit->setLabel($this->getView()->getTranslation('Login'));

        // add the elements to the form
        $this->addElement($id)
             ->addElement($openid)
             ->addElement($submit);
    }
}