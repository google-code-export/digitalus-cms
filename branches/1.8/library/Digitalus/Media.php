<?php

class Digitalus_Media {

    public static function isAllowed($mimeType)
    {
        $filetypes = self::getFiletypes();
        foreach ($filetypes as $type) {
            if ($type->isType($mimeType)) {
                return true;
            }
        }
        return false;
    }

    public static function getFiletypes()
    {
        $config = Zend_Registry::get('config');
        $filetypes = $config->filetypes;
        if ($filetypes) {
            foreach ($filetypes as $key => $filetype) {
                $type = new Digitalus_Media_Filetype($key, $filetype);
                $type->setFromConfigItem($key, $filetype);
                $registeredFiletypes[$key] = $type;
            }
            return $registeredFiletypes;
        }
        return null;
    }

    public static function upload($file, $path, $filename, $createPath = true, $base = '.')
    {
        if (self::isAllowed($file['type'])) {

            $path = self::getMediaPath($path);

            //default to the name on the client machine
            if ($filename == null) {$filename = $file['name'];}
            $filename = str_replace('_','-',$filename);
            $filename = str_replace(' ','-',$filename);

            $path = str_replace(self::rootDirectory(), '', $path);
            $path = Digitalus_Toolbox_String::stripUnderscores($path);
            $path = Digitalus_Toolbox_String::stripLeading('/', $path);

			/*
			 * Update by Brad Seefeld on May 12, 2009
			 *
			 * This fixes an issue when the system is installed on a path other than
			 * root. Path should contain a path that is relative to the (cms) root
			 * index.php (not root to the public_html of the web server (as it was trying
			 * to do before).
			 */
            $config = Zend_Registry::get('config');
            $path = $config->filepath->media . '/' . $path;

            if ($createPath) {
                //attempt to create the new path
                Digitalus_Filesystem_Dir::makeRecursive($base, $path);
            }
            //clean the filename
            $filename = Digitalus_Filesystem_File::cleanFilename($filename);
            $filename = basename($filename);
            $path .= '/' . $filename;

            if (move_uploaded_file($file['tmp_name'], $path)) {
                //return the filepath if things worked out
                //this is relative to the site root as this is the format that will be required for links and what not
                $fullPath = Digitalus_Toolbox_String::stripLeading($base . '/', $path);
                return $fullPath;
            }
        }
    }

    public static function batchUpload($files, $path, $filenames = array(), $createPath = true, $base = '.')
    {
        $filepaths = array();
        if (is_array($files)) {
            for ($i = 0; $i <= (count($files["size"]) - 1);$i++) {

                $file = array(
                    'name'     => $files['name'][$i],
                    'type'     => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error'    => $files['error'][$i],
                    'size'     => $files['size'][$i]
                );
                if (isset($filenames[$i])) {
                    $filename = true;
                } else {
                    $filename = null;
                }
                $result = self::upload($file, $path, $filename, $createPath, $base);
                if ($result != null) {
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
     * @param string $path - the full path
     * @param string $newName - the new name
     */
    public static function renameFolder($path, $newName)
    {
        $path = self::getMediaPath($path);

        //get the new name
        $parent = Digitalus_Toolbox_String::getParentFromPath($path);
        $newpath = $parent . '/' . $newName;
        if (Digitalus_Filesystem_Dir::rename($path, $newpath)) {
            return $newpath;
        } else {
            return false;
        }
    }

    public static function deleteFolder($folder)
    {
        $config = Zend_Registry::get('config');
        if (self::testFilepath($folder)) {
            $folder = Digitalus_Toolbox_String::stripUnderscores($folder);
            $fullPath = self::rootDirectory() . '/' . $folder;

            //move the folder to the trash
            Digitalus_Filesystem_Dir::copyRecursive($fullPath, $config->filepath->trash);
            Digitalus_Filesystem_Dir::deleteRecursive($fullPath);
        }
    }

    public static function deleteFile($file)
    {
        if (self::testFilepath($file)) {
            $filepath = Digitalus_Toolbox_String::stripUnderscores($file);
            $fullpath = self::rootDirectory() . '/' . $filepath;
            if (file_exists($fullpath)) {
                unlink($fullpath);
            }
        }
    }

    public static function testFilepath($filepath)
    {
        //dont allow access outside the media folder
        if (strpos($filepath, './') || strpos($filepath, './')) {
            throw new Zend_Exception('Illegal file access attempt. Operation cancelled!');
            return false;
        } else {
            return true;
        }
    }

    public static function getMediaPath($path, $relative = true)
    {
        $path = Digitalus_Toolbox_String::stripUnderscores($path);

        //make it impossible to get out of the media library
        $path = str_replace('./','',$path);
        $path = str_replace('../','',$path);

        /*
         * Update by Brad Seefeld on May 12, 2009
         * Remove root path from path if it exists in path already.
         */
		$path = str_replace(self::rootDirectory(false), '', $path);

        //remove the reference to media if it exists
        $pathParts = explode('/', $path);
        if (is_array($pathParts)) {
            if ($pathParts[0] == 'media') {
                unset($pathParts[0]);
            }

			/*
			 * Update by Brad Seefeld on May 12, 2009
			 * Remove any leading slash that may exist.
			 */
            $partial = implode('/', $pathParts);
            if(substr($partial, 0, 1) == '/')
            	$partial = substr($partial, 1);

            //add the media root
            $path = self::rootDirectory($relative) . '/' . $partial;

            return $path;
        }
        return false;
    }

    public static function rootDirectory($relative = true)
    {
        $config = Zend_Registry::get('config');
        return APPLICATION_PATH . '/../' . $config->filepath->media;
    }
}
?>