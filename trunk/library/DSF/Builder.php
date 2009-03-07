<?php
class DSF_Builder
{
    const PATH_TO_BUILDERS = './application/data/builders';
    const BASE_CLASSNAME = 'DSF_Builder_Action_';

    public static function loadPage($uri = null, $buildStack = null, DSF_Page $page = null)
    {
    	// fetch the builder stack from config
    	$config = Zend_Registry::get('config');
        if ($buildStack == null) {
            $buildStack = $config->builder->stack;
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
        
        // actions is a stack of all of the builder action files
        $actions = array();
        
        
        foreach ($stack as $action) {
            $attributes = $action->attributes();
            $className = self::BASE_CLASSNAME  . (string)$attributes['class'];
            $methodName = (string)$attributes['method'];

            if(isset($actions[$className])) {
            	$class = $actions[$className];
            }else{
            	$class = new $className($page, $attributes);
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
}