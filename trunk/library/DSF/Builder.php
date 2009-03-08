<?php
class DSF_Builder
{
    const PATH_TO_BUILDERS = './application/data/builders';
    const BASE_CLASSNAME = 'DSF_Builder_Action_';

    public static function loadPage($uri = null, $buildStack = null, DSF_Page $page = null, Zend_View $view = null, $persist = null)
    {
    	// fetch the builder stack from config
    	$config = Zend_Registry::get('config');
        if ($buildStack == null) {
            $buildStack = $config->builder->stack;
        }
        
        // set whether to persist this page as the current page
        if($persist === null) {
            if(strtolower($config->builder->persistPage) == 'true') {
               $persist = true;
            }else{
                $persist = false;
            }
        }
        
        $pathToBuildStack = self::PATH_TO_BUILDERS . '/' . $buildStack;

        //load the builder stack
        $stack = simplexml_load_file($pathToBuildStack);
        
        // get the uri
        $uri = new DSF_Uri($uri);
        $uriArray = $uri->toArray();
        
        //create the page if one is not passed
        if($page == null) {
            $page = new DSF_Page($uriArray);
        }
        
        if($view != null) {
            $page->setView($view);
        }
        
        // actions is a stack of all of the builder action files
        $actions = array();
        
        foreach ($stack as $action) {
            $attributes = $action->attributes();
            $className = self::BASE_CLASSNAME  . (string)$attributes['class'];
            $methodName = (string)$attributes['method'];

            if(isset($actions[$className])) {
            	$class = $actions[$className];
            }else{
            	$class = new $className($page, $attributes, $persist);
            	$actions[$className] = $class;
            }
            
            $class->$methodName($attributes);
        }
        return $page;
    }

    public static function getPage()
    {
        if (Zend_Registry::isRegistered('page')) {
            return Zend_Registry::get('page');
        } else {
            return null;
        }
    }
    
    public static function setPage(DSF_Page $page)
    {
        Zend_Registry::set('page', $page);
    }
}