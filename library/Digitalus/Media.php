<?php

class Digitalus_Media
{
    /**
     * the regex that the userName will be checked against
     */
    const MEDIALABEL_REGEX = '/^[0-9a-zA-Z-_]*$/u';
    /**
     * this is the error message that will be displayed if the userName doesn't match the regex
     */
    const MEDIALABEL_REGEX_NOTMATCH = 'Please only use alphanumeric characters, hyphen and underscore!';


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

    public static function upload($file, $path, $filename = null, $createPath = true, $base = '.')
    {
        $view = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->view;
        $e = new Digitalus_View_Error();

        if ($file['error'] == 4 || empty($file['name'])) {
            return;
        }

        if (self::isAllowed($file['type'])) {
            $path = self::getMediaPath($path);

            //default to the name on the client machine
            if (is_null($filename)) {
                $filename = $file['name'];
            }
            $filename = str_replace('_', '-', $filename);
            $filename = str_replace(' ', '-', $filename);

            $path = str_replace(self::rootDirectory(), '', $path);
            $path = Digitalus_Toolbox_String::stripUnderscores($path);
            $path = Digitalus_Toolbox_String::stripLeading('/', $path);

            /*
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
            } else {
                $e->add($view->getTranslation('An error occurred uploading the file' . ': ' . $file['name']));
            }
        } else {
            $e->add($view->getTranslation('This filetype is not allowed' . ': ' . $file['type']));
        }
    }

    public static function batchUpload($files, $path, $filenames = array(), $createPath = true, $base = '.')
    {
        $filepaths = array();
        if (is_array($files)) {
            for ($i = 0; $i <= (count($files['size']) - 1); $i++) {

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
        }
        return false;
    }

    public static function deleteFolder($folder)
    {
        $config = Zend_Registry::get('config');
        if (self::testFilepath($folder)) {
            $folder   = Digitalus_Toolbox_String::stripUnderscores($folder);
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
        $view = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->view;
        //dont allow access outside the media folder
        if (strpos($filepath, './') || strpos($filepath, './')) {
            require_once 'Digitalus/Media/Exception.php';
            throw new Digitalus_Media_Exception($view->getTranslation('Illegal file access attempt. Operation cancelled!'));
            return false;
        } else {
            return true;
        }
    }

    public static function getMediaPath($path, $relative = true)
    {
        $path = Digitalus_Toolbox_String::stripUnderscores($path);

        //make it impossible to get out of the media library
        $path = str_replace('./', '', $path);
        $path = str_replace('../', '', $path);

        //remove root path from path if it exists in path already.
        $path = str_replace(self::rootDirectory(false), '', $path);

        //remove the reference to media if it exists
        $pathParts = explode('/', $path);
        if (is_array($pathParts)) {
            if ($pathParts[0] == 'media') {
                unset($pathParts[0]);
            }

            //remove any leading slash that may exist.
            $partial = implode('/', $pathParts);
            if (substr($partial, 0, 1) == '/')
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