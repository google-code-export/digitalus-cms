<?php
class DSF_Builder
{
	const PATH_TO_BUILDERS = './application/data/builders';
	const DEFAULT_BUILDER = 'default.xml';
	const BASE_CLASSNAME = 'DSF_Builder_Action_';
	
	static function loadPage($buildStack = null)
	{
		if($buildStack == null) {
			$buildStack = self::DEFAULT_BUILDER;
		}
		
		$pathToBuildStack = self::PATH_TO_BUILDERS . '/' . $buildStack;
		
		//load the builder stack
		$stack = simplexml_load_file($pathToBuildStack);
		foreach ($stack as $action) {
			$attributes = $action->attributes();
			$className = self::BASE_CLASSNAME  . (string)$attributes['class'];
			$methodName = (string)$attributes['method'];
			
			$class = new $className($attributes);
			$response = $class->$methodName($attributes); 
		}
		return $response;
	}
	
	static function getPage()
	{
		if(Zend_Registry::isRegistered('page')) {
			return Zend_Registry::get('page');
		}else{
			return null;
		}
	}
}