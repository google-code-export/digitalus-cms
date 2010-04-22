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
 * @version     $Id: Registration.php Mon Dec 24 20:38:38 EST 2007 20:38:38 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */

/**
 * @see Login_Challenge
 */
require_once APPLICATION_PATH . '/modules/login/models/Challenge.php';

/**
 * @see Model_User
 */
require_once APPLICATION_PATH . '/admin/models/User.php';

/**
 * Registration model
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */
class Login_Registration
{
    public $view;

    /**
     * @var Login_Challenge Challenge object
     */
    protected $_challenge;

    /**
     * Array with replaceable text fragments.
     *
     * @var array
     */
    protected $_textSearch = array();

    /**
     * Array with replacing text fragments.
     *
     * @var array
     */
    protected $_textReplace = array();

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->setView();
        $this->_challenge = new Login_Challenge();
    }

    /**
     * Creates a new user
     *
     * @param  string $userName  User name to create User for
     * @param  string $firstName User's first name
     * @param  string $lastName  User's last name
     * @param  string $email     User's email address
     * @param  string $password  User's password
     * @param  string $active    User's activity status
     * @param  string $role      User's acl role
     * @return mixed  False if unsuccessful, otherwise Model_User object
     */
    public function createUser($userName, $firstName, $lastName, $email, $password, $active = 0, $role = Model_Group::GUEST_ROLE)
    {
        $mdlUser = new Model_User();
        $userName = $mdlUser->createUser($userName, $firstName, $lastName, $email, $password, $active, $role);
        if (!empty($userName) && '' != $userName) {
            return $this->prepareChallenge($userName, $email);
        }
        return false;
    }
    /**
     * Prepares the challenge
     *
     * @param  string $userName  User name to create User for
     * @param  string $email     User's email address
     * @param  string $active    User's activity status
     * @return mixed  False if unsuccessful, otherwise Model_User object
     */
    public function prepareChallenge($userName, $email)
    {
        if ($this->_challenge->insertChallenge($this->_challenge->getChallengeId(), $userName)) {
            return $userName;
        }
        return false;
    }

    /**
     * Sends a confirmation email
     *
     * @param  string $userName     User name to send email to
     * @param  string $emailAddress User's email address
     * @param  string $$emailText   Content of email
     * @return bool   True if successful, otherwise false
     */
    public function sendConfirmationMail($userName, $emailAddress, $emailText, $action = null)
    {
        $settings = new Model_SiteSettings();

        $emailText = $this->_createEmailText($userName, $emailAddress, $emailText, $action);

        $mail = new Zend_Mail();
        $mail->setBodyHtml($emailText, 'utf8');
        $mail->setBodyText(strip_tags($emailText), 'utf8');
        $mail->setFrom($settings->get('default_email'), $settings->get('default_email_sender'));
        $mail->addTo($emailAddress, $userName);
        $mail->setSubject($this->view->getTranslation('Registration'));
        if ($mail->send()) {
            return true;
        }
        return false;
    }

    /**
     * Creates a new user
     *
     * @param  string $userName     User name to send email to
     * @param  string $emailAddress User's email address
     * @param  string $$emailText   Content of email
     * @return bool   True if successful, otherwise false
     */
    protected function _createEmailText($userName, $emailAddress, $emailText, $action = null)
    {
        $this->_textSearch  = array('##USERNAME##', '##EMAIL_ADDRESS##', '##CHALLENGE_URL##');
        $this->_textReplace = array($userName,      $emailAddress,       $this->_challenge->getChallengeUrl(true, $action));

        return str_replace($this->_textSearch, $this->_textReplace, $emailText);
    }

    /**
     * Returns the challenge object
     *
     * @return Login_Challenge  A challenge object
     */
    public function getChallenge()
    {
        return $this->_challenge;
    }

    public function setView(Zend_View $view = null)
    {
        if ($view == null) {
            $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
            if (null === $viewRenderer->view) {
                $viewRenderer->initView();
            }
            $this->view = $viewRenderer->view;
        } else {
            $this->view = $view;
        }
    }

}