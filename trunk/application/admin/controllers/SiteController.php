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
 * @since       Release 1.0.0
 */

/**
 * @see Zend_Controller_Action
 */
require_once 'Zend/Controller/Action.php';

/**
 * Admin Site Controller of Digitalus CMS
 *
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Controllers
 * @version     $Id: SiteController.php Tue Dec 25 19:46:11 EST 2007 19:46:11 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.0.0
 * @uses        Model_SiteSettings
 */
class Admin_SiteController extends Zend_Controller_Action
{
    /**
     * Initialize the action
     *
     * @return void
     */
    public function init()
    {
        $this->view->breadcrumbs = array(
           $this->view->getTranslation('Site Settings') => $this->view->getBaseUrl() . '/admin/site'
        );
    }

    /**
     * The default action
     *
     * Render the main site admin interface
     *
     * @return void
     */
    public function indexAction()
    {
        $form = new Admin_Form_Site();
        $form->setAction($this->view->getBaseUrl() . '/admin/site/edit');

        $this->view->form = $form;

        $this->view->toolbarLinks['Add to my bookmarks'] = $this->view->getBaseUrl() . '/admin/index/bookmark'
            . '/url/admin_site'
            . '/label/' . $this->view->getTranslation('Site');
    }

    /**
     * Edit action
     *
     * Update the site settings file
     *
     * @return void
     */
    public function editAction()
    {
        $form = new Admin_Form_Site();
        $form->setAction($this->view->getBaseUrl() . '/admin/site/edit');

        if ($this->_request->isPost() && $form->isValid($_POST)) {
            $settings = Digitalus_Filter_Post::raw('setting');
            $s = new Model_SiteSettings();
            foreach ($settings as $k => $v) {
                $s->set($k, $v);
/* *****************************************************************************
 * TODO: remove redirector and validate form
 * ****************************************************************************/

/* *****************************************************************************
 * TODO: check allowed charsets for MySQL => VALIDATOR ??
            if ('default_charset' == $k) {
                $v = str_replace('-', '', $v);
                $mdlConfig = new Digitalus_Installer_Config();
                $mdlConfig->loadFile();
                $config = $mdlConfig->get();
                $config->production->database->charset = $v;
                $mdlConfig->save();
            }
 * *****************************************************************************
 * TODO: add Validators for all site meta data => consider using Zend_Form
            if ('default_timezone' == $k) {
                $validator = new Digitalus_Validate_Timezone();
                $_SESSION['timezone0'] = Zend_Debug::dump($v);
                $_SESSION['timezone1'] = Zend_Debug::dump($validator->isValid($v));
            }
 * *****************************************************************************
 */
            }
            $s->save();
            $this->_redirect('admin/site');
        }
        $this->view->form = $form;
    }

    /**
     * Console action
     *
     * The console provides an interface for simple command scripts.
     * those scripts go in library/Digitalus/Command/{script name}
     *
     * @return void
     */
    public function consoleAction()
    {
        //set up a unique id for this session
        $session = new Zend_Session_Namespace('console_session');
        $previousId = $session->id;
        $session->id = md5(time());
        $this->view->consoleSession = $session->id;

        //you must validate that the session ids match
        if ($this->_request->isPost() && !empty($previousId)) {
            $this->view->commandExecuted = true;
            $this->view->command = 'Command: ' . Digitalus_Filter_Post::get('command');
            $this->view->date = time();

            //execute command
            //validate the session

            if (Digitalus_Filter_Post::get('consoleSession') == $previousId) {
                $this->view->lastCommand = Digitalus_Filter_Post::get('command');
                if (Digitalus_Filter_Post::get('runCommand')) {
                   $results = Digitalus_Command::run(Digitalus_Filter_Post::get('command'));
                } else if (Digitalus_Filter_Post::get('getInfo')) {
                    $results = Digitalus_Command::info(Digitalus_Filter_Post::get('command'));
                } else {
                    $results = array('ERROR: invalid request');
                }
            } else {
                $results[] = 'ERROR: invalid session';
            }

            $this->view->results = $results;
        }

        $breadcrumbLabel = $this->view->getTranslation('Site Console');
        $this->view->breadcrumbs[$breadcrumbLabel] = $this->view->getBaseUrl() . '/admin/site/console';
        $this->view->toolbarLinks = array();
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->view->getBaseUrl() . '/admin/index/bookmark/url/admin_site_console';

    }

    /**
     * Mail test action
     *
     * @return void
     */
    public function mailTestAction()
    {
        $settings = new Model_SiteSettings();
        $message = new Digitalus_Mail();
        $message->send(
            $settings->get('default_email'),
            array($settings->get('default_email'), $settings->get('default_email_sender')),
            $this->view->getTranslation('Digitalus CMS Test Message'),
            'Test'
        );
        $this->_forward('index');
    }

}