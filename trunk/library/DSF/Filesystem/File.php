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
 * @version    $Id: File.php Tue Dec 25 20:46:07 EST 2007 20:46:07 forrest lyman $
 */

class DSF_Filesystem_File
{
	/**
	 * this returns an array of all of the files of a set type in a directory path
	 * if type is an array then it will return all files of the types in the array ( $types = array('png', 'jpg', 'gif'); )
	 *
	 * @param string $path, the filepath to search 
	 * @param mixed $type, the file extension to return 
	 * @param string $appendPath, the path to append to the returned files
	 */
	static function getFilesByType($path, $type = false, $appendPath = false, $includeExtension = false)
	{
		if(is_dir($path)){
			$dir = scandir($path); //open directory and get contents
			if(is_array($dir)){ //it found files
				$returnFiles = false;
				foreach ($dir as $file){
				    if(!is_dir($path . '/' . $file)){
    					if($type){ //validate the type
    						$fileParts = explode('.', $file);
    						if(is_array($fileParts)){
    							$c = count($fileParts) - 1; //arrays always start with 0
    							$fileType = $fileParts[$c];
    							
    							//check whether the filetypes were passed as an array or string
    							if(is_array($type)){
    								if(in_array($fileType, $type)){
    								    $filePath =  $appendPath . $file;
    								    if($includeExtension == true) {
    								        $filePath .= '.' . $fileType;
    								    }
    									$returnFiles[] = $filePath;
    								}
    							}else{
    								if($fileType == $type){
    								    $filePath =  $appendPath . $file;
    								    if($includeExtension == true) {
    								        $filePath .= '.' . $fileType;
    								    }
    									$returnFiles[] = $filePath;
    								}
    							}
    						}
    					}else{ //the type was not set.  return all files and directories
    						$returnFiles[] = $file;	
    					}
				    }
				}
				
				if($returnFiles){
					return $returnFiles;
				}
			}
		}
	}
	
	/**
	 * creates a new file from a string
	 *
	 * @param string $path
	 * @param string $content
	 * @return string
	 */
	static function saveFile($path, $content)
	{
		$content = stripslashes($content);
		try{
			file_put_contents($path, $content);
			return 'Your page was saved successfully';
		}catch(Zend_Exception $e) {
			return 'Sorry, there was an error saving your page';
		}
	}
	
	/**
	 * rename the selected file
	 *
	 * @param string $source
	 * @param string $newName
	 */
	static function rename($source, $newName)
	{
    	if(file_exists($source)){
    		rename($source, $newName);
    	}
	}
	
	/**
	 * copy a file
	 *
	 * @param string $source
	 * @param string $target
	 * @return bool
	 */
	static function copy( $source, $target )
    {
        if (file_exists( $source )){
			return copy($source, $target);
        }
    }
    
    /**
     * move a file
     *
     * @param string $source
     * @param string $target
     */
    static function move($source, $target)
    {
    	if(file_exists($source)){
    		rename($source, $target);
    	}
    }
    
    /**
     * delete a file
     *
     * @param string $path
     */
    static function delete($path)
    {
    	@unlink($path);
    }
    
    /**
     * this function cleans up the filename
     * it strips ../ and ./
     * it spaces with underscores
     *
     * @param string $fileName
     */
    static function cleanFilename($fileName)
    {
        $fileName = str_replace('../', '', $fileName);
        $fileName = str_replace('./', '', $fileName);
        $fileName = str_replace(' ', '_', $fileName);
        return $fileName;
    }
    
    static function getFileExtension($filename)
    {
        if(!empty($filename)) {
            $fileparts = explode(".", $filename);
            if(is_array($fileparts)) {
                $index = count($fileparts) - 1;
                $extension = $fileparts[$index];
                return $extension;
            }
        }
        return null;
    }
    
	static function isUploaded($key)
	{
	    if($_FILES[$key] && !empty($_FILES[$key]['tmp_name']))
	    {
	        return true;
	    }
	}
	
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
	static function upload($key, $path, $filename = false, $createPath = true, $base = './')
	{
		if(self::isUploaded($key))
		{
		    //default to the name on the client machine
		    if(!$filename){$filename = $_FILES[$key]['name'];}
		    
		    $path = DSF_Toolbox_String::stripLeading('/', $path);
		    if($createPath)
		    {
		        //attempt to create the new path
		        DSF_Filesystem_Dir::makeRecursive($base, $path);
		    }
		    
		    //clean the filename
		    $filename = DSF_Filesystem_File::cleanFilename($filename);
		    $filename = basename($filename);
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
    
    
}