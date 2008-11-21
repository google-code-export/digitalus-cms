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
 * @package   DSF_Core_Library
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: Mail.php Tue Dec 25 21:49:11 EST 2007 21:49:11 forrest lyman $
 */

class DSF_Mail
{
	/**
	 * the zend view object (used to render the templates)
	 *
	 * @var zend_view
	 */
	private $view;
	
	/**
	 * the mail transport method
	 *
	 * @var zend_mail_transport
	 */
    private $transport = null;
    
    /**
     * the zend mail object
     *
     * @var zend_mail
     */
    private $mail;
    
    /**
     * set up the mail object
     *
     */
    function __construct()
    {
        $this->view = new Zend_View();
        $settings = new SiteSettings();
        if($settings->get('use_smtp_mail') == 1) {
            $config = array('auth' => 'Login',
                            'username' => $settings->get('smtp_username'),
                            'password' => $settings->get('smtp_password'));
            
            $this->transport = new Zend_Mail_Transport_Smtp($settings->get('smtp_host'), $config);
        }
        $this->mail = new Zend_Mail();
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
    function send($recipient, $from=array(), $subject, $template, $data = false, $cc=false)
    {
        $config = Zend_Registry::get('config');
	    $this->view->data = $data;
	    $this->view->addScriptPath($config->filepath->emailTemplates);
		$this->view->emailBody = $this->view->render('templates/' . $template . '.phtml');
		    
        $this->mail->setBodyHtml($this->view->render('template.phtml'));
        $this->mail->setFrom($from[0], $from[1]); 
        
        $this->mail->addTo($recipient);
        
        if($cc){
            $this->mail->addCc($cc);
        }
        $this->mail->setSubject($subject);
        
        return $this->mail->send($this->transport);
    }
}