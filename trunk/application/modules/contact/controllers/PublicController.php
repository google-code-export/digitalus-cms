<?php
require_once './application/modules/contact/forms/Contact.php';

class Mod_Contact_PublicController extends Zend_Controller_Action
{
    public function contactFormAction()
    {
        //create the form
        $form = new Contact_Form();

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
                    $htmlMessage
                );
            }
        }
    }
}