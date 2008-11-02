<?php
class Mod_Contact_IndexController extends Zend_Controller_Action 
{
	
	
	public function init()
	{
		$this->view->adminSection = 'module';
	}
	
	public function indexAction()
	{
		
	}
	
	public function askQuestionAction()
	{
		$s = new SiteSettings('./application/modules/contact/settings.xml');
		if($this->_request->isPost()){
    		$settingsArray = DSF_Filter_Post::raw('setting');
    		foreach ($settingsArray as $k => $v) {
    			$s->set($k, $v);
    		}
    		$s->save();
		}
	   $this->view->settings = $s->toObject();
	}

}