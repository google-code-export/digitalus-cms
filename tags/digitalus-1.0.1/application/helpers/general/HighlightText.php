<?php
class DSF_View_Helper_General_HighlightText
{

	/**
	 * if string is separated into multiple words this will hightlight each indipendantly
	 */
	public function HighlightText($content, $string){
	    //match upper and lower case
	    $upper = explode(' ', ucwords($string));
	    $lower = explode(' ', strtolower($string));
	    
	    $string = array_merge($upper, $lower);
	    
	    foreach ($string as $str){
	        $content = str_replace($str, "[bOp]" . $str . "[bCl]", $content);
	    }
	    
	    $content = str_replace('[bOp]', "<strong class='highlight'>", $content);
	    $content = str_replace("[bCl]", "</strong>", $content);
        return $content;
	}
}