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
 * @version    $Id: Uri.php Tue Dec 25 21:53:29 EST 2007 21:53:29 forrest lyman $
 */

/**
 * this class builds the uri object that the site uses
 *
 */
class DSF_Uri
{
	/**
	 * the clean request uri
	 *
	 * @var string
	 */
    const REGISTRY_KEY = "DSF_URI";
	protected $_uri;
	protected $_base;
	
	/**
	 * load the uri
	 *
	 */
	public function __construct($uri = null)
	{
		if($uri == null) {
			$uri = $_SERVER['REQUEST_URI'];
		}
	    $front = Zend_Controller_Front::getInstance();
	    $this->_base = $front->getBaseUrl();
	    
		$this->_uri = $this->cleanUri($uri);
		
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
		if($relative)
		{
			$uri = $this->getRelative($uri);
		}else{
			$uri = $this->getAbsolute($uri);
		}
		$arr = explode('/', $uri);
		if(is_array($arr))
		{
			foreach ($arr as $part)
			{
				if(!empty($part))
				{
					$return[] = (string)$part;
				}
			}
			if(isset($return))
			{
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
	private function cleanUri($uri)
	{
		$uri = DSF_Toolbox_Regex::stripFileExtension($uri); 
		$uri = DSF_Toolbox_Regex::stripTrailingSlash($uri);
		$uri = urldecode($uri);
		return DSF_Toolbox_String::stripHyphens($uri);
	}
	
	public function getParams()
	{
		$splitPaths = DSF_Toolbox_Array::splitOnValue($this->toArray(), 'p');
		if($splitPaths)
		{
		     return DSF_Toolbox_Array::makeHashFromArray($splitPaths[1]);
		}
		return false;
	}
	
	
	public function getRelative($uri = null)
	{
		if($uri != null)
		{
			$uri = $this->cleanUri($uri);
		}else{
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
	
	static function get($relative = true)
	{
	    $uri = new DSF_Uri();
	    if($relative) {
	        return $uri->getRelative();
	    }else{
	        return $uri->getAbsolute();
	    }
	    
	}
}