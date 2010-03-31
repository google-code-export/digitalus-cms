<?php
class Contact_Form extends Digitalus_Form
{
    public function __construct($options = null) {
        parent::__construct($options);

        $view = $this->getView();

        //this page should post back to itself
        $this->setAction($_SERVER['REQUEST_URI'])
             ->setMethod('post');

        $name = $this->createElement('text', 'name');
        $name->setLabel($view->getTranslation('Your Name') . ': ')
             ->setRequired(true)
             ->addFilter('StripTags')
             ->addErrorMessage($view->getTranslation('Your name is required!'))
             ->setAttrib('size', 30);

        $email = $this->createElement('text', 'email');
        $email->setLabel($view->getTranslation('Your Email') . ': ')
              ->setRequired(true)
              ->addValidator('EmailAddress')
              ->addErrorMessage($view->getTranslation('Invalid email address!'))
              ->setAttrib('size', 30);

        $subject = $this->createElement('text', 'subject');
        $subject->setLabel($view->getTranslation('Subject') . ': ')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addErrorMessage($view->getTranslation('The subject is required!'))
                ->setAttrib('size', 40);

        $message = $this->createElement('textarea', 'message');
        $message->setLabel($view->getTranslation('Message') . ': ')
                ->setRequired(true)
                ->addErrorMessage($view->getTranslation('The message is required!'))
                ->setAttrib('cols', 35)
                ->setAttrib('rows', 10);

        $font = BASE_PATH . '/media/tpl/fonts/AceCrikey.ttf';
        $captcha = new Zend_Form_Element_Captcha(
            'captcha', array(
                'label' => $view->getTranslation("Please verify you're a human"),
                'captcha' => array(
#                    'captcha' => 'Figlet',
                    'captcha' => 'Image',
                    'wordLen' => 6,
                    'timeout' => 300,
                    'font'    => $font,
                    'fontsize'=> 42,
                    'height'  => 100,
                    'width'   => 260,
                ),
            )
        );

        $submit = $this->createElement('submit', 'submitContactForm');
        $submit->setlabel($view->getTranslation('Send Message'))
               ->setAttribs(array('class' => 'submit'));


        $this->addElement($name)
             ->addElement($email)
             ->addElement($subject)
             ->addElement($message)
             ->addElement($captcha)
             ->addElement($submit)
             ->addDisplayGroup(
                array('form_instance', 'name', 'email', 'subject', 'message', 'captcha', 'submitContactForm'),
                'contact',
                array('legend' => $view->getTranslation('Contact'))
            );
    }
}