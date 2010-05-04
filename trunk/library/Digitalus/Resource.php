<?php
/**
 * Digitalus CMS
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
 * @author      Forrest Lyman
 * @category    Digitalus CMS
 * @package     Digitalus
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id$
 */

class Digitalus_Resource
{
    /**
     * the path to the Files dir
     *
     */
    const PATH_TO_FILES = './public/Files';

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
        if (self::isUploaded($key)) {
            //default to the name on the client machine
            if (!$filename) {
                $filename = $_FILES[$key]['name'];
            }

            $path = Digitalus_Toolbox_String::stripLeading('/', $path);
            if ($createPath) {
                //attempt to create the new path
                Digitalus_Filesystem_Dir::makeRecursive(self::PATH_TO_FILES, $path);
            }

            //clean the filename
            $filename = Digitalus_Filesystem_File::cleanFilename($filename);
            $filename = Digitalus_Toolbox_String::getSelfFromPath($filename);
            $fullPath .= "/" . $filename;
            if (move_uploaded_file($_FILES[$key]['tmp_name'], $fullPath)) {
                //return the filepath if things worked out
                //this is relative to the site root as this is the format that will be required for links and what not
                $fullPath = Digitalus_Toolbox_String::stripLeading('./', $fullPath);
                return $fullPath;
            }
        }
    }

    public function isValidUpload($key)
    {
        if (self::isUploaded($key) && self::validateFiletype($key, $this->_fileTypes)) {
            return true;
        }
    }


    public static function isUploaded($key)
    {
        if ($_FILES[$key] && !empty($_FILES[$key]['tmp_name'])) {
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
    public static function validateFiletype($key, $types)
    {
        if ($_FILES[$key]) {
            $fileType = $_FILES[$key]['type'];
            if (in_array($fileType, $types)) {
                return true;
            }
        }
    }
}