<?php

class DSF_Media {
    
    static function isAllowed($mimeType)
    {
        $filetypes = self::getFiletypes();
        foreach ($filetypes as $type) {
            if($type->isType($mimeType)) {
                return true;
            }
        }
        return false;
    }
    
    static function getFiletypes()
    {
        $config = Zend_Registry::get('config');
        $filetypes = $config->filetypes;
        if($filetypes) {
            foreach ($filetypes as $key => $filetype) {
                $type = new DSF_Media_Filetype($key, $filetype);
                $type->setFromConfigItem($key, $filetype);
                $registeredFiletypes[$key] = $type;
            }
            return $registeredFiletypes;
        }
        return null;
    }
    
    static function upload($file, $path, $filename, $createPath = true, $base = '.')
    {
        
        if(self::isAllowed($file['type'])) {
    		//default to the name on the client machine
		    if($filename == null){$filename = $file['name'];}
		    $filename = str_replace('_','-',$filename);
		    $filename = str_replace(' ','-',$filename);
		    
		    $path = str_replace(self::rootDirectory(), '', $path);
		    $path = DSF_Toolbox_String::stripUnderscores($path);
		    $path = DSF_Toolbox_String::stripLeading('/', $path);
		    $path = $base . '/' . self::rootDirectory() . '/' . $path;

		    if($createPath)
		    {
		        //attempt to create the new path
		        DSF_Filesystem_Dir::makeRecursive($base, $path);
		    }
		    
		    //clean the filename
		    $filename = DSF_Filesystem_File::cleanFilename($filename);
		    $filename = basename($filename);
		    $path .= "/" . $filename;
		    
		    if(move_uploaded_file($file['tmp_name'], $path))
		    {
		        //return the filepath if things worked out
		        //this is relative to the site root as this is the format that will be required for links and what not
		        $fullPath = DSF_Toolbox_String::stripLeading($base . '/', $path);
		        return $fullPath;
		    }
        }
    }
    
    static function batchUpload($files, $path, $filenames = array(), $createPath = true, $base = '.')
    {
        if(is_array($files)) {
            for($i = 0; $i <= (count($files) - 1);$i++) {
                $file = array(
                    "name"      => $files["name"][$i],
                	"type"		=> $files["type"][$i],		
                    "tmp_name"	=> $files["tmp_name"][$i],
                    "error"		=> $files["error"][$i],
                    "size"		=> $files["size"][$i]
                );
                if(isset($filenames[$i])) {
                    $filename = true;
                }else{
                    $filename = null;
                }
                $result = self::upload($file, $path, $filename, $createPath, $base);
                if($result != null) {
                    $filepaths[] = $result;
                }
            }
            return $filepaths;
        }
        return false;
     }
     
     /**
      * this function renames a folder
      *
      * @param string $oldPath - the full old path
      * @param string $newName - the new name
      */
     static function renameFolder($oldPath, $newName, $base = '.')
     {
	    //build the path to the media folder
         $path = str_replace(self::rootDirectory(), '', $oldPath);
	    $path = DSF_Toolbox_String::stripUnderscores($path);
	    $path = DSF_Toolbox_String::stripLeading('/', $path);
	    $path = $base . '/' . self::rootDirectory() . '/' . $path;
	    
	    //get the new name
	    $pathParts = explode('/', $path);
	    array_pop($pathParts);
	    $newPath = implode('/', $pathParts);
	    $newPath .= '/' . $newName;
	    
	    if(DSF_Filesystem_Dir::rename($path, $newPath)) {
	        $relativePath = str_replace($base . '/' . self::rootDirectory(). '/', '', $newPath);
	        return $relativePath;
	    }else{
	        return false;
	    }
	    
     }
    
    static function rootDirectory()
    {
        $config = Zend_Registry::get('config');
        return $config->filepath->media;
    }
}

?>