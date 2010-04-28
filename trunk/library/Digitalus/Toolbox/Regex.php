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
 * @version     $Id: Regex.php Tue Dec 25 21:17:02 EST 2007 21:17:02 forrest lyman $
 */

class Digitalus_Toolbox_Regex
{
    /**
     * removes the trailing slash
     *
     * @param string $string
     * @return string
     */
    public static function stripTrailingSlash($string)
    {
        return preg_replace("/\/$/", '', $string);
    }

    /**
     * strips the file extension
     *
     * @param string $string
     * @return string
     */
    public static function stripFileExtension($string, $asArray = false)
    {
        $regexp = "|\.\w{1,5}$|";
        $new = preg_replace($regexp, "", $string);
        $suf = substr($string, strlen($new)+1);
        if ($asArray == true) {
            return array('location' => $new, 'suffix' => $suf);
        } else {
            return $new; // use this return for standard Digitalus setup
        }
    }


    /**
     * returns the html between the the body tags
     * if filter is set then it will return the html between the specified tags
     *
     * @param string $html
     * @param string $filter
     * @return string
     */
    public static function extractHtmlPart($html, $filter = false)
    {
        if ($filter) {
            $startTag = "<{$filter}>";
            $endTag = "</{$filter}>";
        } else {
            $startTag = "<body>";
            $endTag = "</body>";
        }
        $startPattern = ".*" . $startTag;
        $endPattern = $endTag . ".*";

        $noheader = eregi_replace($startPattern, "", $html);

        $cleanPart = eregi_replace($endPattern, "", $noheader);

        return $cleanPart;
    }

    /**
     * replaces multiple spaces with a single space
     *
     * @param string $string
     * @return string
     */
    public static function stripMultipleSpaces($string)
    {
        return trim(preg_replace('/\s+/', ' ', $string));
    }

    /**
     * note that this does not transfer any of the attributes
     *
     * @param string $tag
     * @param string $replacement
     * @param string $content
     */
    public static function replaceTag($tag, $replacement, $content, $attributes = null)
    {
        $content = preg_replace("/<{$tag}.*?>/", "<{$replacement} {$attributes}>", $content);
        $content = preg_replace("/<\/{$tag}>/", "</{$replacement}>", $content);
        return $content;
    }

}