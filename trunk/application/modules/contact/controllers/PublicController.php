<?php
class Mod_Contact_PublicController extends Zend_Controller_Action 
{
    public function contactFormAction()
    {
    	//create the form
	    $form = new Zend_Form();
	    
	    //this page should post back to itself
	    $form->setAction($_SERVER['REQUEST_URI']);
	    $form->setMethod('post');
        
        $name = $form->createElement('text','name');
        $name->setLabel('Your Name: ');
        $name->setRequired(TRUE);
        $name->addFilter('StripTags');
        $name->addErrorMessage('Your name is required!');  
        $name->setAttrib('size',30);
        
        $email = $form->createElement('text','email');
        $email->setLabel('Your Email: ');
        $email->setRequired(TRUE);
        $email->addValidator('EmailAddress');
        $email->addErrorMessage('Invalid email address!');    
        $email->setAttrib('size',30);            
        
        $subject = $form->createElement('text','subject');
        $subject->setLabel('Subject: ');
        $subject->setRequired(TRUE);
        $subject->addFilter('StripTags');
        $subject->addErrorMessage('The subject is required!');
        $subject->setAttrib('size', 40);  
        
        $message = $form->createElement('textarea', 'message');
        $message->setLabel('Message: ');
        $message->setRequired(TRUE);
        $message->addErrorMessage('The message is required!');
        $message->setAttrib('cols', 35);
        $message->setAttrib('rows', 10);
        
        $captcha = new Zend_Form_Element_Captcha('captcha', array(
			'label' => "Please verify you're a human",
			'captcha' => array(
				'captcha' => 'Figlet',
				'wordLen' => 6,
				'timeout' => 300,
			),
		));
        
	    $form->addElement($name);
        $form->addElement($email);
        $form->addElement($subject);
        $form->addElement($message);
        $form->addElement($captcha);
	    $form->addElement('submit', 'submitContactForm', array('label' => 'Send Message'));
	    
	    $this->view->form = $form;
	    
        if($this->_request->isPost() && DSF_Filter_Post::has('submitContactForm'))
        {
        	if($form->isValid($_POST)) {
	       		//get form data
	       		$data = $form->getValues();
	       		
	            //get the module data
	            $module = new DSF_Module();
	            $moduleData = $module->getData();
	            
	            //render the message
	            $this->view->data = $data;
	            $htmlMessage = $this->view->render('public/message.phtml');
	
	    		$mail = new DSF_Mail();
	            $this->view->isSent = $mail->send(
	                $moduleData->email, 
	                array($data['email'], $data['name']), 
	                $data['subject'], 
	                $htmlMessage);
            }
        }
    }
}