<?php
/**
 * the public index controller's sole mission in life is to render content pages
 *
 */
class IndexController extends Zend_Controller_Action
{
	
	public function init()
	{
		$view = new Zend_View();
		$this->_setParam('view',$view);
	}
	
	public function indexAction()
	{		
		DSF_Builder::loadPage();
		
		/*$this->_helper->actionStack('render','index');
		
		$config = Zend_Registry::get('config');
		$stack = $config->pageBuild;
		foreach ($stack->request as $request){
			if($request->params){
				$params = $request->params->toArray();
			}else{
				$params = array();
			}
			
			$this->_helper->actionStack($request->action,$request->controller, 'cmsFront', $params);
		}*/	
	}
	
	public function renderAction()
	{
		die();
	}
}


