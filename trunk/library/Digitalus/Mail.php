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
 * @category   Digitalus CMS
 * @package   Digitalus_Core_Library
 * @copyright  Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: Mail.php Tue Dec 25 21:49:11 EST 2007 21:49:11 forrest lyman $
 */

class Digitalus_Mail
{
    /**
     * the zend view object (used to render the templates)
     *
     * @var zend_view
     */
    private $_view;

    /**
     * the mail transport method
     *
     * @var zend_mail_transport
     */
    private $_transport = null;

    /**
     * the zend mail object
     *
     * @var zend_mail
     */
    private $_mail;

    /**
     * set up the mail object
     *
     */
    public function __construct()
    {
        $this->_view = new Zend_View();
        $settings = new Model_SiteSettings();
        if ($settings->get('use_smtp_mail') == 1) {
            $config = array('auth' => 'Login',
                            'username' => $settings->get('smtp_username'),
                            'password' => $settings->get('smtp_password'));

            $this->_transport = new Zend_Mail_Transport_Smtp($settings->get('smtp_host'), $config);
        }
        $this->_mail = new Zend_Mail();
    }

    /**
     * load the template and send the message
     *
     * @param string $recipient
     * @param array $from
     * @param string $subject
     * @param string $template
     * @param array $data
     * @param string $cc
     * @return bool
     */
    public function send($recipient, $from=array(), $subject, $message, $cc=false)
    {
        $config = Zend_Registry::get('config');
        $this->_view->addScriptPath($config->filepath->emailTemplates);
        $this->_view->emailBody = $message;

        $this->_mail->setBodyHtml($this->_view->render('template.phtml'));
        $this->_mail->setFrom($from[0], $from[1]);

        $this->_mail->addTo($recipient);

        if ($cc) {
            $this->_mail->addCc($cc);
        }
        $this->_mail->setSubject($subject);

        return $this->_mail->send($this->_transport);
    }
}