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
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Module_Login
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: PublicController.php Mon Dec 24 20:38:38 EST 2007 20:38:38 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */

/**
 * @see Admin_Form_User
 */
require_once APPLICATION_PATH . '/admin/forms/User.php';

/**
 * Login User Form
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */
class User_Form extends Admin_Form_User
{
    /**
     * Initialize the form
     *
     * @return void
     */

    public function onlyRegistrationActionElements($attribs = array())
    {
        // remove unwanted elements
        $this->removeElement('openid');
        $this->removeElement('active');
        $this->removeElement('role');
        $this->removeElement('password');
        $this->removeElement('password_confirm');
        $this->removeElement('update_password');

        if (isset($attribs['legend'])) {
            $this->_setLegend($attribs['legend']);
        }
    }

    public function onlyNewpasswordActionElements($attribs = array())
    {
        // remove unwanted elements
        $this->removeElement('first_name');
        $this->removeElement('last_name');
        $this->removeElement('openid');
        $this->removeElement('active');
        $this->removeElement('role');
        $this->removeElement('password');
        $this->removeElement('password_confirm');
        $this->removeElement('update_password');

        if (isset($attribs['legend'])) {
            $this->_setLegend($attribs['legend']);
        }
    }

    public function onlyChangepasswordActionElements($attribs = array())
    {
        // remove unwanted elements
        $this->removeElement('first_name');
        $this->removeElement('last_name');
        $this->removeElement('openid');
        $this->removeElement('active');
        $this->removeElement('role');
        $this->removeElement('email');
        $this->removeElement('update_password');
        $this->removeElement('captcha');

        if (isset($attribs['legend'])) {
            $this->_setLegend($attribs['legend']);
        }
    }

    protected function _setLegend($legend)
    {
        $group = $this->getDisplayGroup('adminUserGroup');
        $group->setLegend($legend);
    }
}