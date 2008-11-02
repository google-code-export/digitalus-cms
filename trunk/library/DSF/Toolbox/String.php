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
 * @version    $Id: String.php Tue Dec 25 21:17:02 EST 2007 21:17:02 forrest lyman $
 */

class DSF_Toolbox_String
{
    /**
     * returns a randomly generated string
     * commonly used for password generation
     *
     * @param int $length
     * @return string
     */
    static function random($length = 8)
    {
      // start with a blank string
      $string = "";
    
      // define possible characters
      $possible = "0123456789abcdfghjkmnpqrstvwxyz"; 
        
      // set up a counter
      $i = 0; 
        
      // add random characters to $string until $length is reached
      while ($i < $length) { 
    
        // pick a random character from the possible ones
        $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
            
        // we don't want this character if it's already in the string
        if (!strstr($string, $char)) { 
          $string .= $char;
          $i++;
        }
    
      }
    
      // done!
      return $string;
    }
    
	/**
	 * replaces spaces with hyphens (used for urls)
	 *
	 * @param string $string
	 * @return string
	 */
	static function addHyphens($string)
	{
		return str_replace(' ', '-', trim($string));
	}
	
	/**
	 * replaces hypens with spaces
	 *
	 * @param string $string
	 * @return string
	 */
	static function stripHyphens($string)
	{
		return str_replace('-', ' ', trim($string));
	}
	
	/**
	 * replace slashes with underscores
	 *
	 * @param string $string
	 * @return string
	 */
	static function addUnderscores($string, $relative = false)
	{
		return str_replace('/', '_', trim($string));
	}
	
	/**
	 * replaces underscores with slashes
	 * if relative is true then return the path as relative
	 * 
	 * @param string $string
	 * @param bool $relative
	 * @return string
	 */
	static function stripUnderscores($string, $relative = false)
	{
		$string = str_replace('_', '/', trim($string));
		if($relative)
		{
			$string = DSF_Toolbox_String::stripLeading('/', $string);
		}
		return $string;
	}
	
	/**
	 * strips the leading $replace from the $string
	 *
	 * @param string $replace
	 * @param string $string
	 * @return string
	 */
	static function stripLeading($replace, $string)
	{
		if(substr($string, 0, strlen($replace)) == $replace)
		{
			return substr($string, strlen($replace));
		}else{
			return $string;
		}
	}
	
	/**
	 * returns the parent from the passed path
	 *
	 * @param string $path
	 * @return string
	 */
	static function getParentFromPath($path)
	{
		$path = DSF_Toolbox_Regex::stripTrailingSlash($path);
		$parts = explode('/', $path);
		array_pop($parts);
		return implode('/', $parts);
	}
	
	/**
	 * returns the current file from the path
	 * this is a custom version of basename
	 *
	 * @param string $path
	 * @return string
	 */
	static function getSelfFromPath($path)
	{
		$path = DSF_Toolbox_Regex::stripTrailingSlash($path);
		$parts = explode('/', $path);
		return array_pop($parts);
	}
}