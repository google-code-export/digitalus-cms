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
 * @version    $Id: Interfaceh.php Tue Dec 25 20:30:05 EST 2007 20:30:05 forrest lyman $
 */

class DSF_Controller_Plugin_LayoutLoader extends Zend_Controller_Plugin_Abstract
{
    public $pathToViews;
    public $view;
    public $layout;
    
    public $module;
    public $section; //this is the admin section
    public $controller;
    public $action;
    
    public $sections = array(
        'index'         =>  'index',
        'site'          =>  'site',
        'user'          =>  'site',
        'page'          =>  'page',
        'module'        =>  'module' 
    );
    public $defaultSection = 'index';
    
    /**
     * this function routes all requests that come in to the default module to the index controller / index action
     *
     * @param zend_controller_request $request
     */
    public function preDispatch($request)
    {
	    //load the module, controller, and action for reference
	    $this->module = $request->getModuleName();
	    $this->controller = $request->getControllerName();
	    $this->action = $request->getActionName();
	    
	    //load the section
	    if(isset($this->sections[$this->controller])){
	        $this->section = $this->sections[$this->controller];
	    }else{
	        $this->section = $this->defaultSection;
	    }

		if($this->isAdminPage($request))
		{
		    //load config
		    $config = Zend_Registry::get('config');
		    
		    //setup layout
		    $options = array(
                'layout'     => $config->design->adminLayout,
                'layoutPath' => $config->design->adminLayoutFolder,
                'contentKey' => 'form',           // ignored when MVC not used
            );
		    $this->layout = Zend_Layout::startMvc($options);
		    $this->view = $this->layout->getView();
		    
		    //load the common helpers
            DSF_View_RegisterHelpers::register($this->view);
            $this->view->setScriptPath($config->filepath->adminViews);
            
            //load language files
    
            $translate = null;
            foreach ($config->language->translations as $locale => $translation){
                if(is_object($translate)) {
                   $translate->addTranslation($config->language->path . '/' . $translation . '.csv', $locale); 
                }else{
                    $translate = new Zend_Translate('csv', $config->language->path . '/' . $translation . '.csv', $locale);
                }
            }
            $locale = $config->language->defaultLocale;
            $translate->setLocale($locale);
            $translate->setCache(Zend_Registry::get('cache'));
            $this->view->translate = $translate;
            
            //page links
            $this->view->toolbarLinks = array();
            
		}    	
    }
    
    private function isAdminPage($request) {
		if($this->module != 'public' && $this->controller != 'public' && !$request->isXmlHttpRequest()){
		    return true;
		}
    }
}