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
 * @author      Forresst Lyman
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_Toolbox
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: String.php Tue Dec 25 21:17:02 EST 2007 21:17:02 forrest lyman $
 */

class Digitalus_Toolbox_String
{
    /**
     * returns a randomly generated string
     * commonly used for password generation
     *
     * @param int $length
     * @return string
     */
    public static function random($length = 8)
    {
        // start with a blank string
        $string = '';

        // define possible characters
        $possible = '0123456789abcdfghjkmnpqrstvwxyz';

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
    public static function addHyphens($string)
    {
        return str_replace(' ', '-', trim($string));
    }

    /**
     * replaces hypens with spaces
     *
     * @param string $string
     * @return string
     */
    public static function stripHyphens($string)
    {
        return str_replace('-', ' ', trim($string));
    }

    /**
     * replace slashes with underscores
     *
     * @param string $string
     * @return string
     */
    public static function addUnderscores($string, $relative = false)
    {
        $string = str_replace('_', '[UNDERSCORE]', $string);
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
    public static function stripUnderscores($string, $relative = false)
    {
        $string = str_replace('_', '/', trim($string));
        if ($relative) {
            $string = Digitalus_Toolbox_String::stripLeading('/', $string);
        }
        $string = str_replace('[UNDERSCORE]', '_', $string);
        return $string;
    }

    /**
     * strips the leading $replace from the $string
     *
     * @param string $replace
     * @param string $string
     * @return string
     */
    public static function stripLeading($replace, $string)
    {
        if (substr($string, 0, strlen($replace)) == $replace) {
            return substr($string, strlen($replace));
        } else {
            return $string;
        }
    }

    /**
     * returns the parent from the passed path
     *
     * @param string $path
     * @return string
     */
    public static function getParentFromPath($path)
    {
        $path = Digitalus_Toolbox_Regex::stripTrailingSlash($path);
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
    public static function getSelfFromPath($path)
    {
        $path = Digitalus_Toolbox_Regex::stripTrailingSlash($path);
        $parts = explode('/', $path);
        return array_pop($parts);
    }

    public static function truncateText($text, $count = 25, $stripTags = true)
    {
        if ($stripTags) {
            $filter = new Zend_Filter_StripTags();
            $text   = $filter->filter($text);
        }
        $words = split(' ', $text);
        $text  = (string)join(' ', array_slice($words, 0, $count));
        return $text;
    }
}