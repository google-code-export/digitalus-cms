<?php
class Zend_View_Helper_RealPath
{

	/**
	 * returns the full path to teh page
	 */
	public function RealPath($pageId){
	    $c= new Content();
	    $page = $c->find($pageId)->current();
	    if($page){
    	    $parents = $c->getParents($pageId);
    	    if(is_array($parents) && count($parents) > 0){
    	        $path = implode('/', $parents) . '/';
    	    } 
    	    $fullpath =  $path . $page->title;
    	    return strtolower($fullpath);
	    }	    
	}
}
