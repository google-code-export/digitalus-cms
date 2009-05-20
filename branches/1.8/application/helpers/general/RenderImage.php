<?php
class Digitalus_View_Helper_General_RenderImage
{

	/**
	 * comments
	 */
	public function renderImage($src, $height, $width,$attribs = false){
	    $absPath = SITE_ROOT . $src;
		if($src != '' && is_file($absPath)){
		    
    		$imageSize = getimagesize($absPath);
    		$srcHeight = $imageSize[0];
    		$srcWidth = $imageSize[1];

    		//if the height is greater than the width then adjust by the height
    		//otherwise adjust by the width
    		if($srcHeight > $srcWidth){
    			$percentage = $height / $srcHeight;
    		}else{
    			$percentage = $width / $srcWidth;
    		}
    		
    		//gets the new value and applies the percentage, then rounds the value
    		$width = round($srcWidth * $percentage);
    		$height = round($srcHeight * $percentage);
    		
    		if($attribs){
    			foreach ($attribs as $k => $v) {
    				$attributes .= $k . "='" . $v . "' ";
    			}
    		}else{
    		    $attributes = null;
    		}
    		return "<img width='{$width}' heigth='{$height}' src='{$src}' {$attributes}/>";
		}
	}
}