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
 * @see Login_Challenge
 */
require_once APPLICATION_PATH . '/modules/login/models/Challenge.php';

/**
 * @see Login_Password
 */
require_once APPLICATION_PATH . '/modules/login/models/Password.php';

/**
 * @see Login_Registration
 */
require_once APPLICATION_PATH . '/modules/login/models/Registration.php';

/**
 * @see User_Form
 */
require_once APPLICATION_PATH . '/modules/login/forms/User.php';

/**
 * Public Controller
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */
class Mod_Login_PublicController extends Digitalus_Controller_Action
{
    /**
     * Module data stored in db.
     * @var string
     */
    public    $moduleData;

    /**
     * Module properties from xml file
     * @var string
     */
    public    $properties;

    /**
     * Errors
     * @var string
     */
    protected $_error   = '';

    /**
     * Messages
     * @var string
     */
    protected $_message = '';

    /**
     * Called from __construct() as final step of object instantiation.
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        $module           = new Digitalus_Module();
        $this->moduleData = $module->getData();
        $this->properties = Digitalus_Module_Property::load('mod_login');
    }

    /**
     * The default public action - display login form and login
     *
     * @return void
     */
    public function indexAction()
    {
        $loginForm = new Admin_Form_Login();
        $loginForm->setAction($this->baseUrl . '/' . Digitalus_Toolbox_Page::getCurrentPageName());

        if ($this->_request->isPost() && $loginForm->isValid($_POST)) {
            $username = Digitalus_Filter_Post::get('adminUsername');
            $password = Digitalus_Filter_Post::get('adminPassword');

            $auth = new Digitalus_Auth($username, $password);
            $result = $auth->authenticate();
            if (is_null($result)) {
                $e = new Digitalus_View_Error();
                $e->add($this->view->getTranslation('The username or password you entered was not correct.'));
#            } else {
#                $this->_redirect(Digitalus_Toolbox_Page::getHomePageName());
            }
        }
        $this->view->form = $loginForm;
    }

    /**
     * The public registration action - display registration form and register
     *
     * @return void
     */
    public function registrationAction()
    {
        $registrationForm = new User_Form();
        $registrationForm->setAction($this->baseUrl . '/' . Digitalus_Toolbox_Page::getCurrentPageName() . '/p/a/registration');

        $registrationForm->getElement('name')->addValidators(array(
            array('UsernameExistsNot', true),
        ));

        $registrationForm->onlyRegistrationActionElements(array('legend' => 'Register'));

        // show form if unsent or invalid
        if (!$this->_request->isPost() || !$registrationForm->isValid($_POST)) {
            $this->view->form = $registrationForm;
        } else {
            $mdlUser         = new Model_User();
            $mdlRegistration = new Login_Registration();
            $mdlPassword     = new Login_Password();
            $password  = $mdlPassword->getRandomPassword();
            $userName  = Digitalus_Filter_Post::get('name');
            $firstName = Digitalus_Filter_Post::get('first_name');
            $lastName  = Digitalus_Filter_Post::get('last_name');
            $email     = Digitalus_Filter_Post::get('email');
            $success   = false;
            if ($mdlRegistration->createUser($userName, $firstName, $lastName, $email, $password, 0, $this->moduleData->aclRole)) {
                if ($mdlRegistration->sendConfirmationMail($userName, $email, $this->moduleData->email)) {
                    $success = true;
                }
            }
            $this->view->userName = $userName;
            $this->view->email    = $email;
            $this->view->password = $password;
            $this->view->success  = $success;
            unset($password);
        }
    }

    /**
     * The public newpassword action - display newpassword form and set a new password
     *
     * @return void
     */
    public function newpasswordAction()
    {
        $newPasswordForm = new User_Form();
        $newPasswordForm->setAction($this->baseUrl . '/' . Digitalus_Toolbox_Page::getCurrentPageName() . '/p/a/newpassword');

        $newPasswordForm->getElement('name')->addValidators(array(
            array('UsernameExists', true),
        ));
        $newPasswordForm->getElement('email')->addValidators(array(
            array('UserEmailExists', true),
        ));

        $newPasswordForm->onlyNewpasswordActionElements(array('legend' => 'New password'));

        // show form if unsent or invalid
        if ($this->_request->isPost() && $newPasswordForm->isValid($_POST)) {
            $userName = Digitalus_Filter_Post::get('name');
            $email    = Digitalus_Filter_Post::get('email');
            $this->view->userName = $userName;
            $this->view->email    = $email;

            $mdlRegistration = new Login_Registration();
            $mdlRegistration->prepareChallenge($userName, $email);
            if ($mdlRegistration->sendConfirmationMail($userName, $email, $this->moduleData->email, 'changepassword')) {
                $this->view->success = true;
            }
        } else {
            $this->view->form = $newPasswordForm;
        }
    }

    /**
     * The public challenge action - get parameters from uri and validate challenge
     *
     * @return void
     */
    public function challengeAction()
    {
        $uri = new Digitalus_Uri();
        $uriParams = $uri->getParams();

        if (!isset($uriParams['u']) || !isset($uriParams['c'])) {
            $this->_error;
        } else {
            $userName    = $uriParams['u'];
            $challengeId = $uriParams['c'];

            $mdlChallenge = new Login_Challenge();
            if (!$mdlChallenge->isValid($challengeId, $userName)) {
                $this->_error = $this->view->getTranslation('Error: No valid challenge was found. Please register again!');
            } else {
                $mdlUser = new Model_User();
                if (!$mdlUser->activate($userName)) {
                    $this->_error = $this->view->getTranslation('Error: could not activate user!');
                } else {
                    $mdlChallenge->invalidate($challengeId);
                    $this->_message = $this->view->getTranslation('Congratulations!<br />Your user has been activated successfully!<br /><br />Have a lot of fun in the restricted area!');
                }
            }
        }
        $this->view->error   = $this->_error;
        $this->view->message = $this->_message;
    }

    /**
     * The public challenge action for getting a new password
     *
     * @return void
     */
    public function changepasswordAction()
    {
        $uri = new Digitalus_Uri();
        $uriParams = $uri->getParams();

        if (!isset($uriParams['u']) || !isset($uriParams['c'])) {
            $this->_error;
        } else {
            $userName    = $uriParams['u'];
            $challengeId = $uriParams['c'];

            $mdlChallenge = new Login_Challenge();
            if (!$mdlChallenge->isValid($challengeId, $userName)) {
                $this->_error = $this->view->getTranslation('Error: No valid challenge was found. Please try again!');
            } else {
                $changePasswordForm = new User_Form();
                $uri = $this->baseUrl . '/' . Digitalus_Toolbox_Page::getCurrentPageName(). '/p/a/changepassword/u/' . $userName . '/c/' . $challengeId;
                $changePasswordForm->setAction($uri);
                $changePasswordForm->getElement('name')->addValidators(array(
                    array('UsernameExists', true),
                ));
                $changePasswordForm->onlyChangepasswordActionElements(array('legend' => 'Change Password'));

                if ($this->_request->isPost() && $changePasswordForm->isValid($_POST)) {
                    $password        = Digitalus_Filter_Post::get('password');
                    $passwordConfirm = Digitalus_Filter_Post::get('password_confirm');
                    $mdlUser = new Model_User();
                    if (!$mdlUser->updatePassword($userName, $password, true, $passwordConfirm)) {
                        $this->_error   = $this->view->getTranslation("Error: Your password hasn't been updated!");
                    } else {
                        $mdlChallenge->invalidate($challengeId);
                        $this->_message = $this->view->getTranslation('Your password has been updated successfully!');
                    }
                } else {
                    $this->_message = $this->view->getTranslation('Please type in Your user name and Your new password.');
                    $this->view->form = $changePasswordForm;
                }
            }
        }
        $this->view->error   = $this->_error;
        $this->view->message = $this->_message;
    }
}