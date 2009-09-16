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
 * @since       Release 1.0.0
 */

/**
 * @see Zend_Controller_Action
 */
require_once 'Zend/Controller/Action.php';

/**
 * Admin Auth Controller of Digitalus CMS
 *
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Controllers
 * @version     $Id: AuthController.php Mon Dec 24 20:48:35 EST 2007 20:48:35 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.0.0
 * @uses        Admin_Form_Login
 * @uses        Admin_Form_OpenId
 */
class Admin_AuthController extends Zend_Controller_Action
{
    /**
     * Initialize the action
     *
     * @return void
     */
    public function init()
    {
        $this->view->breadcrumbs = array(
           $this->view->getTranslation('Login') => $this->getFrontController()->getBaseUrl() . '/admin/auth/login'
        );
    }

    /**
     * Login action
     *
     * if the form has not been submitted this renders the login form
     * if it has then it validates the data
     * if it is sound then it runs the Digitalus_Auth_Adapter function
     * to authorise the request
     * on success it redirects to the admin home page
     *
     * @return void
     */
    public function loginAction()
    {
        $form = new Admin_Form_Login();

        if ($this->_request->isPost() && $form->isValid($_POST)) {
            $uri = Digitalus_Filter_Post::get('uri');
            $username = $form->getValue('adminUsername');
            $password = $form->getValue('adminPassword');

            $auth = new Digitalus_Auth($username, $password);
            $result = $auth->authenticate();
            if ($result) {
                if ($uri == '' || $uri == 'admin/auth/login') {
                    $uri = 'admin';
                }
                $this->_redirect($uri);
            } else {
                $e = new Digitalus_View_Error();
                $e->add($this->view->getTranslation('The username or password you entered was not correct.'));
            }
            $this->view->uri = $uri;
        } else {
            $this->view->uri = Digitalus_Uri::get();
        }
        $this->view->form = $form;
    }

    /**
     * OpenID Login action
     *
     * @return void
     */
    public function openidAction()
    {
        $form = new Admin_Form_OpenId();

        $this->view->status = '';
#        if (($this->_request->isPost()
        if (($this->_request->isPost() && $form->isValid($_POST)
             && $form->getValue('openid_action') == $this->view->getTranslation('Login')
             && $form->getValue('openid_identifier') !== '') ||
            ($this->_request->isPost() && $this->_request->getPost('openid_mode') !== null) ||
            (!$this->_request->isPost() && $this->_request->getQuery('openid_mode') != null)
           ) {
            $auth = Zend_Auth::getInstance();
            $openidStorage = new Zend_OpenId_Consumer_Storage_File(BASE_PATH . '/cache/');
            $result = $auth->authenticate(
                new Zend_Auth_Adapter_OpenId($form->getValue('openid_identifier'), $openidStorage)
            );
            if ($result->isValid()) {
                $storage = new Zend_Session_Namespace(Digitalus_Auth::USER_NAMESPACE);
                $userMdl = new Model_User();
                $user = $userMdl->getUserByOpenId($_SESSION['zend_openid']['identity']);
                $storage->user = $user;

                $this->_redirect('admin/auth/openid');
            } else {
                $auth->clearIdentity();
                foreach ($result->getMessages() as $message) {
                    $this->view->status .= $message . '<br>' . PHP_EOL;
                }
            }
        }
        $this->view->form = $form;

#        $this->render();
#Zend_Debug::dump($_SESSION);
#Zend_Debug::dump($_POST);
#Zend_Debug::dump($_GET);

echo 'STATUS: ' . $this->view->status . '<br />';
    }

    /**
     * Logout action
     *
     * kills the authorized user object
     * then redirects to the main index page
     *
     * @return void
     */
    public function logoutAction()
    {
        Digitalus_Auth::destroy();
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect('/');
    }

    /**
     * Reset password action
     *
     * @return void
     */
    public function resetPasswordAction()
    {
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            $email = Digitalus_Filter_Post::get('email');
            $user = new Model_User();
            $match = $user->getUserByUsername($email);
            if ($match) {
                //create the password
                $password = Digitalus_Toolbox_String::random(10); //10 character random string

                //load the email data
                $data['first_name'] = $match->first_name;
                $data['last_name'] = $match->last_name;
                $data['username'] = $match->email;
                $data['password'] = $password;

                //get standard site settings
                $s = new Model_SiteSettings();
                $settings = $s->toObject();

                //attempt to send the email
                $mail = new Digitalus_Mail();
                if ($mail->send($match->email, array($sender), 'Password Reminder', 'passwordReminder', $data)) {
                    //update the user's password
                    $match->password = md5($password);
                    $match->save();//save the new password
                    $m = new Digitalus_View_Message();
                    $m->add(
                        $this->view->getTranslation('Your password has been reset for security and sent to your email address')
                    );
                } else {
                    $e = new Digitalus_View_Error();
                    $e->add(
                        $this->view->getTranslation('Sorry, there was an error sending you your updated password. Please contact us for more help.')
                    );
                }
            } else {
                $e = new Digitalus_View_Error();
                $e->add(
                    $this->view->getTranslation('Sorry, we could not locate your account. Please contact us to resolve this issue.')
                );
            }
            $url = 'admin/auth/login';
            $this->_redirect($url);
        }
    }

}