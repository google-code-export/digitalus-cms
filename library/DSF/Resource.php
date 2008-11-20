<?php 

/**
 * DSF CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://digitalus-media.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@digitalus-media.com so we can send you a copy immediately.
 *
 * @category   DSF CMS
 * @package   DSF_Core_Library
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: Resource.php Tue Dec 25 21:52:35 EST 2007 21:52:35 forrest lyman $
 */

class DSF_File
{
	/**
	 * the path to the Files dir
	 *
	 */
    const PATH_TO_FileS = './public/Files';

	/**
	 * you pass this function the key for the $_FILES[key] array 
	 * and the new filepath to move the file to
	 * then add the new filename
	 * if $createPath is true then this will attempt to create the directories required for the path
	 *
	 * @param string $key
	 * @param string $path
	 * @param string $filename
	 * @param bool $createPath
	 */
	public function upload($key, $path, $filename = false, $createPath = true)
	{
		if(self::isUploaded($key))
		{
		    //default to the name on the client machine
		    if(!$filename){$filename = $_FILES[$key]['name'];}
		    
		    $path = DSF_Toolbox_String::stripLeading('/', $path);
		    if($createPath)
		    {
		        //attempt to create the new path
		        DSF_Filesystem_Dir::makeRecursive(self::PATH_TO_FileS, $path);
		    }
		    
		    //clean the filename
		    $filename = DSF_Filesystem_File::cleanFilename($filename);
		    $filename = DSF_Toolbox_String::getSelfFromPath($filename);
		    $fullPath .= "/" . $filename;
		    if(move_uploaded_file($_FILES[$key]['tmp_name'], $fullPath))
		    {
		        //return the filepath if things worked out
		        //this is relative to the site root as this is the format that will be required for links and what not
		        $fullPath = DSF_Toolbox_String::stripLeading('./', $fullPath);
		        return $fullPath;
		    }
		}
	}
	
	public function isValidUpload($key)
	{
	    if(self::isUploaded($key) && self::validateFiletype($key, $this->_fileTypes)){
	        return true;
	    }
	}

	
	static function isUploaded($key)
	{
	    if($_FILES[$key] && !empty($_FILES[$key]['tmp_name']))
	    {
	        return true;
	    }
	}
	
	/**
	 * this function validates that the uploaded file's mime type is in the array of types
	 *
	 * @param string $key
	 * @param array $types
	 * @return boolean
	 */
	static function validateFiletype($key, $types){
	    if($_FILES[$key])
	    {
	        $fileType = $_FILES[$key]['type'];
	        if(in_array($fileType, $types)){
	            return true;
	        }
	    }
	}
}