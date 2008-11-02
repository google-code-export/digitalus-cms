<?php
class Mod_Contact_PublicController extends DSF_Controller_Module_Public
{
    public function contactFormAction()
    {
        if($this->_request->isPost())
        {
            $pageId = $this->_request->getParam('pageId');
            $p = new Properties($pageId);
            $moduleData = $p->getGroup('modules')->items;
            $settings = new SiteSettings();
            
            $m = new DSF_View_Message();
            $e = new DSF_View_Error();
            
            $sender = DSF_Filter_Post::get('email');
            $name = DSF_Filter_Post::get('name');
            $subject = DSF_Filter_Post::get('subject');
            $data['name'] = $name;
            $data['sender'] = $sender;
            $data['message'] = DSF_Filter_Post::get('message');
            
            if(DSF_Filter_Post::int('copyMe') == 1)
            {
                $cc = $sender;
            }            
            
    		$mail = new DSF_Mail();
            if($mail->send($moduleData->params['email'], array($sender), $subject, 'contactForm', $data, $cc))
            {
                $m->add($moduleData->params['successMessage']);
            }else{
                $e->add($moduleData->params['errorMessage']);
            }
            
            //autoresponse
            if(!empty($moduleData->params['autoresponse_message']))
            {
                unset($data);
                $data['autoresponse'] = $moduleData->params['autorespond'];
                $response = new DSF_Mail();
                $response->send(
                    $sender, 
                    array($moduleData->params['email'], $moduleData->params['recipient']), 
                    $moduleData->params['autoresponse_subject'], 
                    'autoresponder',
                    $data);
            }
        
        }
		
        $this->view->recipient = $this->_request->getParam('recipient');
    }
    
    public function askQuestionAction()
    {
        if($this->_request->isPost())
        {
    		$s = new SiteSettings('./application/modules/contact/settings.xml');
    	    $settings = $s->toObject();
            
            $sender = DSF_Filter_Post::get('email');
            $name = DSF_Filter_Post::get('name');
            $subject = "New question from " . $name;
            $data['name'] = $name;
            $data['sender'] = $sender;
            $data['message'] = DSF_Filter_Post::get('question');

    		$mail = new DSF_Mail();
            
            if($mail->send($settings->recipient, array($sender), $subject, 'contactForm', $data, $cc))
            {
                $this->view->questionResponse = "Thanks for contacting me.  I will answer your question ASAP!";
            }else{
                $this->view->questionResponse = "Sorry, there was an error submitting your question";
            }

            //autoresponse
            if($settings->autoResponse == 1)
            {
                unset($data);
                $data['autoresponse'] = $settings->autoMessage;
                $response = new DSF_Mail();
                $response->send(
                    $sender, 
                    array($moduleData->params['email'], $moduleData->params['recipient']), 
                    $settings->autoSubject, 
                    'autoresponder',
                    $data);
            }
     
        }
    }
}