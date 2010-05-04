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
 * @version     $Id$
 */

class Digitalus_Toolbox_Array
{
    /**
     * finds the selected value, then splits the array on that key, and returns the two arrays
     * if the value was not found then it returns false
     *
     * @param array $array
     * @param string $value
     * @return mixed
     */
    public static function splitOnValue($array, $value)
    {
        if (is_array($array)) {
            $paramPos = array_search($value, $array);

            if ($paramPos) {
                $arrays[] = array_slice($array, 0, $paramPos);
                $arrays[] = array_slice($array, $paramPos + 1);
            } else {
                $arrays = null;
            }
            if (is_array($arrays)) {
                return $arrays;
            }
        }
        return null;
    }

    /**
     * takes a simple array('value','3','othervalue','4')
     * and creates a hash using the alternating values:
     * array(
     *  'value' => 3,
     *  'othervalue' => 4
     * )
     *
     * @param array $array
     */
    public static function makeHashFromArray($array)
    {
        $hash = null;

        if (is_array($array) && count($array) > 1) {
            for ($i = 0; $i <= count($array); $i+= 2) {
                if (isset($array[$i])) {
                    $key = $array[$i];
                    $value = $array[$i + 1];
                    if (!empty($key) && !empty($value)) {
                       $hash[$key] = $value;
                    }
                }
            }
        }

        if (is_array($hash)) {
            return $hash;
        }
    }

        /**
     * takes an array:
     * $groups = array(
     *     'group1' => "<h2>group1......",
     *     'group2' => "<h2>group2...."
     *     );
     *
     * and splits it into 2 equal (more or less) groups
     * @param unknown_type $groups
     */
    public static function splitGroups($groups)
    {
        foreach ($groups as $k => $v) {
            //set up an array of key = count
            $g[$k] = strlen($v);
            $totalItems += $g[$k];
        }

        //the first half is the larger of the two
        $firstHalfCount = ceil($totalItems / 2);

        //now go through the array and add the items to the two groups.
        $first=true;
        foreach ($g as $k => $v) {
            if ($first) {
                $arrFirst[$k] = $groups[$k];
                $count += $v;
                if ($count > $firstHalfCount) {
                    $first = false;
                }
            } else {
                $arrSecond[$k] = $groups[$k];
            }
        }

        $arrReturn['first']=$arrFirst;
        $arrReturn['second']=$arrSecond;
        return $arrReturn;
    }

    /**
     * this function builds an associative array from a standard get request string
     * eg: animal=dog&sound=bark
     * will return
     * array(
     *     animal => dog,
     *     sound => bark
     * )
     *
     * @param string $getParams
     * @return array
     */
    public static function arrayFromGet($getParams)
    {
        $parts = explode('&', $getParams);
        if (is_array($parts)) {
            foreach ($parts as $part) {
                $paramParts = explode('=', $part);
                if (is_array($paramParts) && count($paramParts) == 2) {
                    $param[$paramParts[0]] = $paramParts[1];
                    unset($paramParts);
                }
            }
        }
        return $param;
    }
}