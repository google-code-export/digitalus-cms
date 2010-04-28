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
 * @category    Digitalus CMS
 * @package     Digitalus
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: Uri.php Tue Dec 25 21:53:29 EST 2007 21:53:29 forrest lyman $
 */

/**
 * this class builds the uri object that the site uses
 *
 */
class Digitalus_Uri
{
    /**
     * the clean request uri
     *
     * @var string
     */
    const REGISTRY_KEY = 'Digitalus_URI';
    protected $_uri;
    protected $_params;
    protected $_base;

    /**
     * load the uri
     *
     */
    public function __construct($uri = null)
    {
        if ($uri == null) {
            $uri = $_SERVER['REQUEST_URI'];
        }
        $front = Zend_Controller_Front::getInstance();
        $this->_base = $front->getBaseUrl();

        $this->_uri = $this->_cleanUri($uri);

        Zend_Registry::set(self::REGISTRY_KEY, $this);

    }

    /**
     * if uri is not set then it will return the uri that was set in the constructor
     *
     * @param string $uri
     * @return array
     */
    public function toArray($relative = true, $uri = null)
    {
        if ($relative) {
            $uri = $this->getRelative($uri);
        } else {
            $uri = $this->getAbsolute($uri);
        }
        $arr = explode('/', $uri);
        if (is_array($arr)) {
            foreach ($arr as $part) {
                if (!empty($part)) {
                    $return[] = (string)$part;
                }
            }
            if (isset($return)) {
                return $return;
            }
        }
    }

    /**
     * returns the uri as a string
     *
     * @return string
     */
    public function toString()
    {
        return $this->_uri;
    }


    /**
     * cleans the uri
     *
     * @param string $uri
     * @return string
     */
    private function _cleanUri($uri)
    {
        $uri = Digitalus_Toolbox_Regex::stripFileExtension($uri);
        $uri = Digitalus_Toolbox_Regex::stripTrailingSlash($uri);
        $uri = urldecode($uri);
        $array = explode('/', $uri);
        $splitPaths = Digitalus_Toolbox_Array::splitOnValue($array, 'p');
        if (is_array($splitPaths)) {
            $uri = implode('/', $splitPaths[0]);
            if (is_array($splitPaths[1])) {
                $this->_params = Digitalus_Toolbox_Array::makeHashFromArray($splitPaths[1]);
            }
        }
#        return Digitalus_Toolbox_String::stripHyphens($uri);
        return str_replace(' ', '_', trim($uri));
    }

    public function getParams()
    {
        if (is_array($this->_params)) {
            return $this->_params;
        }
        return false;
    }


    public function getRelative($uri = null)
    {
        if ($uri != null) {
            $uri = $this->_cleanUri($uri);
        } else {
            $uri = $this->_uri;
        }
        return str_replace($this->_base, null, $uri);
    }

    public function getAbsolute($uri = null)
    {
        //clean the uri first
        $uri = $this->getRelative($uri);

        //then append the base
        return $this->_base . $uri;
    }

    public static function get($relative = true, $stripParams = true, $addParams = null)
    {
        $uri = new Digitalus_Uri();
        if ($relative) {
            $uriString = $uri->getRelative();
        } else {
            $uriString = $uri->getAbsolute();
        }
        $params = array();
        if ($stripParams == false) {
            $params = $uri->getParams();
        }
        if (is_array($addParams)) {
            foreach ($addParams as $k => $v) {
                $params[$k] = $v;
            }
        }
        $paramsString = null;
        if (is_array($params)) {
             foreach ($params as $k => $v) {
                 $paramsString .= '/' . $k . '/' . $v;
             }
        }

        if ($paramsString != null) {
            $uriString .= '/p' . $paramsString;
        }
        return $uriString;
    }
}