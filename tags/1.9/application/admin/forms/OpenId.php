<?php
class Admin_Form_OpenId extends Digitalus_Form
{
    public function init()
    {
        $this->setAction($this->getView()->getBaseUrl() . '/admin/auth/openid')
             ->setMethod('post');

        // create new element
        $id = $this->createElement('hidden', 'id');
        // element options
        $id->setDecorators(array('ViewHelper'));

        // create new element
        $openid = $this->createElement('text', 'openid_identifier');
        // element options
        $openid->setLabel($this->getView()->getTranslation('OpenID'))
               ->setRequired(true)
               ->setAttribs(array('size' => 50, 'class' => 'openid_login'))
#               ->addValidator('Hostname', false, Zend_Validate_Hostname::ALLOW_DNS)
               ->setErrorMessages(array($this->getView()->getTranslation('You must enter a valid OpenID.')));

        $submit = $this->createElement('submit', 'openid_action');
        $submit->setLabel($this->getView()->getTranslation('Login'));

        // add the elements to the form
        $this->addElement($id)
             ->addElement($openid)
             ->addElement($submit);
    }
}