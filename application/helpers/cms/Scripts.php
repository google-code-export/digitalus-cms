<?php
class DSF_View_Helper_Cms_Scripts
{

	/**
	 * inserts the code to include a script
	 * pretty simple stuff, but nice and clean in the view
	 * 
	 * @param files, array
	 */
	public function Scripts($files){
	    //get the style path
	    $config = Zend_Registry::get('config');
	    
	    //build xhtml
	    $xhtml = "\n<!--Beginning of scripts-->\n";
	    foreach ($files as $file) {
	    	$path = '/' . $config->filepath->script . '/' . $file;
            $xhtml .= "\t<script type='text/javascript' src='{$path}'></script> \n";
	    }
	    $xhtml .= "<!--End of scripts-->\n";
		return $xhtml;
	}
}