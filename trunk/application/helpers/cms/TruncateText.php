<?php
class DSF_View_Helper_Cms_TruncateText
{
	/**
	 * returns a truncated version of the text
	 *
	 * @param unknown_type $text
	 * @param unknown_type $count
	 * @return unknown
	 */
	function truncateText($text, $count = 25, $stripTags = true)
	{
		if($stripTags){
		    $filter = new Zend_Filter_StripTags();
    		$text = $filter->filter($text);
		}
		$words=split(" ",$text); 
		$text = (string)join(" ",array_slice($words,0,$count));
        return $text;
	}
}