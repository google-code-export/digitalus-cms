<?php
class DSF_View_Helper_General_ReplaceBoldWithStrong
{

	public function ReplaceBoldWithStrong($content, $strongClass = null)
	{
	    if($strongClass){
	        $class = "class='{$strongClass}'";
	    }
	    
	    //get the content body
	    $content = DSF_Toolbox_Regex::extractHtmlPart($content, 'body');
	    
	    //replace the tags
	    $content = DSF_Toolbox_Regex::replaceTag('b', 'strong', $content, $class) ;
        return $content;
	}
}