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
 * @package    DSF_CMS_Controllers
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: SiteController.php Tue Dec 25 19:46:11 EST 2007 19:46:11 forrest lyman $
 */

class Core_SiteController extends Zend_Controller_Action 
{
	public function init()
	{
		//set the admin section
		$this->view->adminSection = 'site';
	}
	
	/**
	 * render the main site admin interface
	 *
	 */
	public function indexAction()
	{
		$settings = new SiteSettings();
		$this->view->settings = $settings->toObject();
	}
	
	/**
	 * update the site settings file
	 *
	 */
	public function editAction()
	{
		$settings = DSF_Filter_Post::raw('setting');
		$s = new SiteSettings();
		foreach ($settings as $k => $v) {
			$s->set($k, $v);
		}
		$s->save();
		$this->_redirect('/admin/site');
	}
	
	/**
	 * render the traffic report
	 *
	 */
	public function trafficAction()
	{
		$log = new TrafficLog();
		$this->view->hitsThisWeek = $log->getLogByDay();
		$this->view->hitsByWeek = $log->getLogByWeek();
	}
	
	/**
	 * render the admin access log
	 *
	 */
	public function adminAccessAction()
	{
		$log = new TrafficLog();
		$this->view->accessLog = $log->adminAccess();
	}
	
	/**
	 * the console provides an interface for simple command scripts.
	 * those scripts go in library/DSF/Command/{script name}
	 *
	 */
	public function consoleAction()
	{
	    //set up a unique id for this session
	    $session = new Zend_Session_Namespace('console_session');
	    $previousId = $session->id;
	    $session->id = md5(time());
	    $this->view->consoleSession = $session->id;
	    	    
	    //you must validate that the session ids match
	    if($this->_request->isPost() && !empty($previousId))
	    {
	        $this->view->commandExecuted = true;
	        $this->view->command = "Command: " . DSF_Filter_Post::get('command');
	        $this->view->date = time();
	        
	        //execute command
	        //validate the session
	        
	        if(DSF_Filter_Post::get('consoleSession') == $previousId)
	        {
	            $this->view->lastCommand = DSF_Filter_Post::get('command');
	            if(DSF_Filter_Post::get('runCommand'))
	            {
	               $results = DSF_Command::run(DSF_Filter_Post::get('command'));
	            }elseif (DSF_Filter_Post::get('getInfo'))
	            {
	                $results = DSF_Command::info(DSF_Filter_Post::get('command'));
	            }else{
	                $results = array('ERROR: invalid request');
	            }
	        }else{
	            $results[] = "ERROR: invalid session";
	        }
	        
	        $this->view->results = $results;
	    }
	}
	
	
}
