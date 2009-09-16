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
 * @category   Digitalus CMS
 * @package   Digitalus_Core_Library
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: Image.php Tue Dec 25 21:12:41 EST 2007 21:12:41 forrest lyman $
 */

class Digitalus_Resource_Image extends Digitalus_Resource
{
    /**
     * the image path (within the resources directory)
     *
     */
    const IMAGE_PATH = 'images';

    /**
     * the default thumbnail width
     *
     */
    const THUMB_WIDTH = 120;

    /**
     * the default full size image width
     *
     */
    const FULL_WIDTH = 400;

    /**
     * the image source
     *
     * @var binary image
     */
    protected $_image;

    /**
     * path to the source file
     *
     * @var string
     */
    protected $_srcPath;

    /**
     * valid image mime types
     */
    protected $_fileTypes = array(
        'image/gif',
        'image/jpeg',
        'image/png'
    );

    /**
     * the image compression settings. php5 / gd changed the png range from 1-100 to 1-10
     *
     * @var array
     */
    protected $_imageQuality = array(
        'png'   =>  9,
        'jpeg'  =>  90,
        'gif'   =>  90
    );

    /**
     * the relative filepath to the full sized image
     *
     * @var string
     */
    public $fullPath;

    /**
     * the relative filepath to the thumbnail
     *
     * @var string
     */
    public $thumbPath;

    /**
     * the file type
     *
     * @var string
     */
    public $fileType;

    /**
     * the image caption
     *
     * @var string
     */
    public $caption;

    /**
     * uploads the selected file (the key is the name of the files control)
     * if successfull then it resizes the images, creating a thumbnail and a full size image
     * if then removes the source file
     *
     * @param string $key
     */
    public function upload($key, $subdir = null, $resize = true, $makeThumb = true, $thumbWidth = null, $fullWidth = null)
    {
        //try to upload the file
        if ($subdir !== null) {
            $path = self::IMAGE_PATH . '/' . $subdir;
        } else {
            $path = self::IMAGE_PATH;
        }

        $upload = parent::upload($key, $path);
        if ($upload) {
            if ($resize == true) {
                //make the full sized image
                if ($fullWidth == null) {
                    $fullWidth = self::FULL_WIDTH;
                }
                $this->fullPath = $this->resize($upload, $fullWidth, 'full_');
            } else {
                $this->fullPath = $upload;
            }

            if ($makeThumb) {
                //make the thumbnail
                if ($thumbWidth == null) {
                    $thumbWidth = self::THUMB_WIDTH;
                }
                $this->thumbPath = $this->resize($upload, $thumbWidth, 'thumb_');
            }

            if ($resize == true) {
                //remove the source file
                unlink($upload);
            }
        }
    }

    /**
     * resizes images
     * if append is set then it will append this string to the new filename
     * it returns the path to the resized file
     *
     * @param string $path
     * @param int $newwidth
     * @param string $append
     * @return string
     */
    public function resize($path, $newwidth, $append = null)
    {
        $fName = basename($path);

        // Create an Image from it so we can do the resize
        $upload = $this->_openImage($path);
        if ($upload) {
            // Capture the original size of the imagedie
            $width = imagesx($this->_image);
            $height = imagesy($this->_image);

            $newheight=$height * ($newwidth/$width);
            $tmp=imagecreatetruecolor($newwidth, $newheight);
            // this line actually does the image resizing, copying from the original
            // image into the $tmp image
            imagecopyresampled($tmp, $this->_image, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

            // now write the resized image to disk.
            $newPath = str_replace($fName, $append . $fName, $path);
            $imageFunction = 'image' . $this->fileType;

            $imageFunction($tmp, $newPath, $this->_imageQuality[$this->fileType]);

            imagedestroy($this->_image);
            imagedestroy($tmp); // NOTE: PHP will clean up the temp file it created when the request
            return $newPath;
        } else {
            require_once 'Digitalus/Resource/Exception.php';
            throw new Digitalus_Resource_Exception('Could not open image file');
        }
    }

    /**
     * tests the selected image and opens it with the proper function
     *
     * @param string $file
     * @return bool
     */
    private function _openImage($file)
    {
        # JPEG:
        $im = @imagecreatefromjpeg($file);
        if ($im !== false) {
            $this->fileType = 'jpeg';
            $this->_image = $im;
            return true;
        }

        # GIF:
        $im = @imagecreatefromgif ($file);
        if ($im !== false) {
            $this->fileType = 'gif';
            $this->_image = $im;
            return true;
        }

        # PNG:
        $im = @imagecreatefrompng($file);
        if ($im !== false) {
            $this->fileType = 'png';
            $this->_image = $im;
            return true;
        }

        return false;
    }
}