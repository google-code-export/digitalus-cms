<?php
class DSF_View_Helper_General_RenderDate
{

	/**
	 * defaults to current date
	 * we use php's formating here
	 */
	public function RenderDate($timestamp = null, $format = 'F j, Y'){
	    if($timestamp == null){
	        $timestamp = time();
	    }
	    Zend_Date::setOptions(array('format_type' => 'php'));
	    $date = new Zend_Date($timestamp);
        return $date->toString($format);
	}
}