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
 * @version     $Id: GroupAcl.php 701 2010-03-05 16:23:59Z lowtower@gmx.de $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10.0
 */

/**
 * @see Digitalus_Form
 */
require_once 'Digitalus/Form.php';

/**
 * Admin User Group Acl Form
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Admin
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10.0
 * @uses        Model_Group
 */
class Admin_Form_GroupAcl extends Digitalus_Form
{
    /**
     * The name of the group
     *
     * @var string
     */
    protected $_groupName;

    /**
     * Initialize the form
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        $view = $this->getView();

        $hidden = $this->createElement('hidden', 'name');

        $groups = $view->selectGroup('from_groupname', null, null, $this->_groupName, 'superadmin');

        $submit = $this->createElement('submit', 'update', array(
            'label'     => $view->getTranslation('Copy Permissions'),
            'attribs'   => array('class' => 'submit'),
        ));

        $this->addElement($hidden)
             ->addElement($groups)
             ->addElement($submit)
             ->setAttribs(array('class' => 'padding-10'));
    }

    public function setGroupName($groupName)
    {
        $this->_groupName = $groupName;

        $this->getElement('name')->setValue($this->_groupName);
        $this->getElement('from_groupname')->removeMultiOption($this->_groupName);
    }
}