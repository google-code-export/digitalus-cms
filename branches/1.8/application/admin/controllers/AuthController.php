<?php
/**
 * DSF CMS
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
 * @category   DSF CMS
 * @package    DSF_CMS_Controllers
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: AuthController.php Mon Dec 24 20:48:35 EST 2007 20:48:35 forrest lyman $
 */

class Admin_AuthController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->breadcrumbs = array(
           $this->view->GetTranslation('Login') => $this->getFrontController()->getBaseUrl() . '/admin/auth/login'
        );
    }

    /**
     * if the form has not been submitted this renders the login form
     * if it has then it validates the data
     * if it is sound then it runs the DSF_Auth_Adapter function
     * to authorise the request
     * on success it redirct to the admin home page
     *
     */
    public function loginAction()
    {
        if ($this->_request->isPost()) {
            $uri = DSF_Filter_Post::get('uri');
            $username = DSF_Filter_Post::get('adminUsername');
            $password = DSF_Filter_Post::raw('adminPassword');

            $e = new DSF_View_Error();

            if ($username == '') {
                $e->add($this->view->GetTranslation('You must enter a username.'));
            }
            if ($password == '') {
                $e->add($this->view->GetTranslation('You must enter a password.'));
            }


            if (!$e->hasErrors()) {
                $auth = new DSF_Auth($username, $password);
                $result = $auth->authenticate();
                if ($result) {
                    if ($uri == '' || $uri == 'admin/auth/login') {
                        $uri = 'admin';
                    }
                     $this->_redirect($uri);
                } else {
                    $e = new DSF_View_Error();
                    $e->add($this->view->GetTranslation('The username or password you entered was not correct.'));
                }
            }
            $this->view->uri = $uri;
        } else {
            $this->view->uri = DSF_Uri::get();
        }

    }

    /**
     * kills the authorized user object
     * then redirects to the main index page
     *
     */
    public function logoutAction()
    {
        DSF_Auth::destroy();
        $this->_redirect('/');
    }

    public function resetPasswordAction()
    {
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            $email = DSF_Filter_Post::get('email');
            $user = new Model_User();
            $match = $user->getUserByUsername($email);
            if ($match) {
                //create the password
                $password = DSF_Toolbox_String::random(10); //10 character random string

                //load the email data
                $data['first_name'] = $match->first_name;
                $data['last_name'] = $match->last_name;
                $data['username'] = $match->email;
                $data['password'] = $password;

                //get standard site settings
                $s = new Model_SiteSettings();
                $settings = $s->toObject();

                //attempt to send the email
                $mail = new DSF_Mail();
                if ($mail->send($match->email, array($sender), 'Password Reminder', 'passwordReminder', $data)) {
                    //update the user's password
                    $match->password = md5($password);
                    $match->save();//save the new password
                    $m = new DSF_View_Message();
                    $m->add(
                        $this->view->GetTranslation('Your password has been reset for security and sent to your email address')
                       );
                } else {
                    $e = new DSF_View_Error();
                    $e->add(
                        $this->view->GetTranslation('Sorry, there was an error sending you your updated password.  Please contact us for more help.')
                       );
                }
            } else {
                $e = new DSF_View_Error();
                $e->add(
                    $this->view->GetTranslation('Sorry, we could not locate your account. Please contact us to resolve this issue.')
                );
            }
            $url =  'admin/auth/login';
            $this->_redirect($url);
         }
    }

}