<?php
class DSF_View_Helper_Cms_StyleSheets
{

	/**
	 * inserts the code to include a style sheet
	 * pretty simple stuff, but makes it easier than inserting your base url every time
	 * if default is true it will insert the base url tothe default style directory
	 * 
	 * @param files, array
	 */
	public function StyleSheets($files, $default = true){
	    //get the style path
	    $config = Zend_Registry::get('config');
	    
	    //build xhtml
	    $xhtml = "\n<!--Begining of style sheets-->\n";
	    foreach ($files as $file) {
	        if($default){
	    	  $path = '/' . $config->filepath->style . '/' . $file;
	        }else{
	            $path = $file;
	        }
            $xhtml .= "\t<link rel='stylesheet' type='text/css' media='screen' href='{$path}' /> \n";
	    }
	    $xhtml .= "<!--End of style sheets-->\n";
		return $xhtml;
	}
}