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

        $name = $form->createElement('text', 'name');
        $name->setLabel($this->view->getTranslation('Your Name') . ': ');
        $name->setRequired(true);
        $name->addFilter('StripTags');
        $name->addErrorMessage($this->view->getTranslation('Your name is required!'));
        $name->setAttrib('size', 30);

        $email = $form->createElement('text', 'email');
        $email->setLabel($this->view->getTranslation('Your Email') . ': ');
        $email->setRequired(true);
        $email->addValidator('EmailAddress');
        $email->addErrorMessage($this->view->getTranslation('Invalid email address!'));
        $email->setAttrib('size', 30);

        $subject = $form->createElement('text', 'subject');
        $subject->setLabel($this->view->getTranslation('Subject') . ': ');
        $subject->setRequired(true);
        $subject->addFilter('StripTags');
        $subject->addErrorMessage($this->view->getTranslation('The subject is required!'));
        $subject->setAttrib('size', 40);

        $message = $form->createElement('textarea', 'message');
        $message->setLabel($this->view->getTranslation('Message') . ': ');
        $message->setRequired(true);
        $message->addErrorMessage($this->view->getTranslation('The message is required!'));
        $message->setAttrib('cols', 35);
        $message->setAttrib('rows', 10);

        $captcha = new Zend_Form_Element_Captcha('captcha', array(
            'label' => $this->view->getTranslation('Please verify you\'re a human'),
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
        $form->addElement('submit', 'submitContactForm', array('label' => $this->view->getTranslation('Send Message')));

        $this->view->form = $form;

        if ($this->_request->isPost() && Digitalus_Filter_Post::has('submitContactForm')) {
            if ($form->isValid($_POST)) {
                //get form data
                $data = $form->getValues();

                //get the module data
                $module = new Digitalus_Module();
                $moduleData = $module->getData();

                //render the message
                $this->view->data = $data;
                $htmlMessage = $this->view->render('public/message.phtml');

                $mail = new Digitalus_Mail();
                $this->view->isSent = $mail->send(
                    $moduleData->email,
                    array($data['email'], $data['name']),
                    $data['subject'],
                    $htmlMessage);
            }
        }
    }
}