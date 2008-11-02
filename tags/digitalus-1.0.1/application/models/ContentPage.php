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
 * @package    DSF_CMS_Models
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: ContentPage.php Sun Dec 23 10:01:01 EST 2007 10:01:01 forrest lyman $
 */

class ContentPage extends Content 
{
    
    const INDEX_PAGE_ID = 1;
	/**
	 * the current page object
	 *
	 * @var stdClass
	 */
	private $_page;
	
	/**
	 * the parents of the current page
	 *
	 * @var array
	 */
	private $_parents = array();
	
	/**
	 * the content type
	 *
	 * @var string
	 */
	protected $_type = 'page';
	
	/**
	 * site settings object
	 *
	 * @var stdClass 
	 */
	protected $_settings;
	
    /**
     * the uri without params
     *
     * @var string
     */
    protected $_cleanUri;
	
	/**
	 * an array of the uri passed parameters
	 * this is loaded from anything after /p/ in the request uri
	 *
	 * @var array
	 */
	protected $_params;
	
	protected $_properties = null;
	
	protected $_headers = array();
	
	protected $_responseCode = null;
	
	/**
	 * takes a uri and loads the correct page
	 *
	 * @param string $uri
	 */
	public function __construct($uri = false)
	{
		parent::__construct();
		$this->_settings = new SiteSettings();
		if($uri || $uri === null)
		{
			$this->setPage($uri);			
		}

	}
	
	//this class is read only data, overload the crud functions
	public function insert(){return false;}
	public function update(){return false;}
	public function delete(){return false;}
	
	/**
	 * builds the current page object
	 *
	 * @param string $uri
	 * @return bool, whether the page was loaded successfully
	 */
	public function setPage($uri)
	{
		if(!$this->isOnline())
		{
			//if the site is not online then load the offline.phtml file
			$this->_page = new stdClass();
			$this->_page->content =$this->loadFromTemplateFile('offline.phtml', 'Sorry, the site is currently offline');
		}else{
			if(is_null($uri))
			{
				$this->setIndexPage();
			}else{
			    //check for a redirector
			    $redirector = new Redirector();
			    $currRedirect = $redirector->getCurrentRedirector();
			    if($currRedirect){
			        $uri = $currRedirect->path;
		            $this->_responseCode = $currRedirect->responseCode;
			        if(!is_array($uri)){
			            //if this is a valid http uri then redirect immediately
			            $this->_headers[] = "location: " . $uri;
			            return;
			        }
			    }
			    
			    //split the uri from the parameters
			    $this->splitUri($uri);
			    if($this->_cleanUri == null){
			        $this->setIndexPage();
			    }else{
    				//find the content page
    				$parentId = 0;
    				foreach($this->_cleanUri as $part)
    				{
    					$where[] = $this->_db->quoteInto("(title = ? OR label = ?)", $part);
    					$where[] = $this->_db->quoteInto("parent_id = ?", $parentId);
    					$row = $this->fetchRow($where);
    					if(!$row)
    					{
    						$this->set404();
    						return false;
    					}
    					unset($where);
    					$parentId = $row->id;
    					$this->_parents[] = $row;
    				}
    				$this->_page = array_pop($this->_parents);
			    }
				
				$this->logHit($this->_page->id);
				
				if(empty($this->_page->content))
				{
					$this->_page->content = $this->loadFromTemplateFile('construction.phtml', 'This page is currently under construction');
				}
			}
        	//set the page properties
        	$this->setProperties();
			return true;
		}
		
		
	}
	
	public function setIndexPage()
	{
	    //return the home page
		$this->_page = $this->find(self::INDEX_PAGE_ID)->current();
	}
	
	/**
	 * splits a uri array into the uri and the parameters
	 *
	 * @param array $uri
	 */
	public function splitUri($uri)
	{
	    if($uri[0] == 'p'){
	        //this is a parameter for the index page
	        array_shift($uri);
	        $this->_cleanUri = null;
		    $this->_params = DSF_Toolbox_Array::makeHashFromArray($uri);
	    }else{
    	    //set the page parameters if they are passed
    		$splitPaths = DSF_Toolbox_Array::splitOnValue($uri, 'p');
    		if($splitPaths)
    		{
    		    $this->_cleanUri = $splitPaths[0];
    		    $this->_params = DSF_Toolbox_Array::makeHashFromArray($splitPaths[1]);
    		}else{
    		    $this->_cleanUri = $uri;   
    		}
	    }
	}
	
	/**
	 * returns the request uri with any params stripped
	 * if asArray is true this will return the uri as an array
	 *
	 * @param bool $asArray
	 * @return unknown
	 */
	public function getCleanUri($asArray = false)
	{
	    if($asArray)
	    {
    	    return $this->_cleanUri;
	    }else{
	        if(is_array($this->_cleanUri)){
	           return implode('/', $this->_cleanUri);
	        }
	    }
	}
	
	/**
	 * returns the current page object
	 *
	 * @return stdClass object
	 */
	public function getPage()
	{
		return $this->_page;
	}
	
	/**
	 * returns the parameter's value if it is set
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function getParam($key)
	{
	    if(isset($this->_params[$key]))
	    {
	        return $this->_params[$key];
	    }
	}
	
	/**
	 * returns all of the url parameters
	 *
	 * @return array
	 */
	public function getParams()
	{
	    return $this->_params;
	}
	
	/**
	 * returns the current page template
	 *
	 * @return string
	 */
	public function getTemplate()
	{
		if(!empty($this->_page->template_path))
		{
			return $this->_page->template_path;
		}else{
			//if the page does not have a template set then use the parents
			$parents = $this->getParents('asc');
			foreach ($parents as $parent)
			{
				if(!empty($parent->template_path))
				{
					return $parent->template_path;
				}
			}
		}
		//if the parents wasnt set then return the default set in config
		$config = Zend_Registry::get('config');
		return $config->design->defaultTemplate;		
	}
	
	/**
	 * returns the current page layout
	 *
	 * @return string
	 */
	public function getLayout()
	{
		if(!empty($this->_page->layout_path))
		{
			return $this->_page->layout_path;
		}else{
			//if the page does not have a layout set then use the parents
			$parents = $this->getParents('asc');
			foreach ($parents as $parent)
			{
				if(!empty($parent->layout_path))
				{
					return $parent->layout_path;
				}
			}
		}
		//if the parents wasnt set then return the default set in config
		$config = Zend_Registry::get('config');
		return $config->design->defaultLayout;
	}
	
	/**
	 * returns the page headline
	 * defaults to the page title if the headline is not set
	 * @return string
	 */
	public function getHeadline($page = null)
	{
	    if($page === null){
	        $page = $this->_page;
	    }
		if(!empty($page->headline))
		{
			return $page->headline;
		}else{
			return $page->title;
		}
	}
	
	/**
	 * returns the current page title
	 * this is built from the current path:
	 * base name - parent - page
	 *
	 * @param string $separator
	 * @return string
	 */
	public function getTitle($separator = ' - ')
	{
		$site = new SiteSettings();
		$parts[] = $site->get('name');
		$parents = $this->_parents;
		if(is_array($parents) && count($parents) > 0){
    		foreach ($parents as $parent)
    		{
    			$parts[] = $this->getLabel($parent);
    		}
		}
		$parts[] = $this->getLabel($this->_page);
		return implode($separator, $parts);
	}
	
	public function setProperties()
	{
	    if($this->_properties == null){
	        //only load this once per request
	        $this->_properties = new Properties($this->_page->id);
	    }
	    return $this->_properties;
	}
	
	public function getProperty($key, $group = 'general')
	{
	    if(is_object($this->_properties)){
	        return $this->_properties->get($key, $group);
	    } 
	    
	}
	
	/**
	 * returns the current page's main module if it is set
	 * @todo revisit how we are handling modules
	 *
	 * @return dsf_data_list
	 */
	public function getModule()
	{
	    if(isset($this->_properties->list->items->modules)){
    	    return $this->_properties->list->items->modules->items;
	    }
	}
	
	
	
	/**
	 * returns any headers that have been set
	 *
	 * @return array
	 */
	public function getHeaders()
	{
	    if(count($this->_headers) > 0){
	        return $this->_headers;
	    }else{
	        return false;
	    }
	}
	
	/**
	 * returns the server response code if it has been set
	 *
	 * @return int
	 */
	public function getResponseCode()
	{
	    if($this->_responseCode != null){
	        return $this->_responseCode;
	    }
	}
	
	/**
	 * evaluates whether to show the site or not
	 * if the current user is a super admin this overrides the online setting
	 * so an admin can debug the site offline
	 *
	 * @return bool
	 */
	public function isOnline()
	{
		$user = DSF_Auth::getIdentity();//if the super admin is logged in then show em the site
		if($this->_settings->get('online') == 1 || $user->role == 'superadmin')
		{
			return true;
		}
	}
	
	/**
	 * returns the parents of the current page
	 * desc = from the home page down to the current page
	 * asc = from the current page up to the home page
	 *
	 * @param str $order
	 * @return unknown
	 */
	public function getParents($order = 'desc')
	{
		if($order == 'desc')
		{
			return $this->_parents;
		}else{
			return array_reverse($this->_parents);
		}
	}
	
	public function getChildren()
	{
	    return parent::getChildren($this->_page->id, true);
	}
    
	/**
	 * sets the page content to the 404 message and logs the request in the 404 error log
	 *
	 */
	private function set404()
	{
		// log the error
		$e = new ErrorLog();
		$e->log404();
		
		//add the 404 header
		$this->_responseCode = 404;
		
		//return the 404 message
		$this->_page = new stdClass();
		$this->_page->content = $this->loadFromTemplateFile('404.phtml');
	}
	
	/**
	 * loads static content from the template dir
	 *
	 * @param string $file
	 * @param string $default
	 * @return string
	 */
	private function loadFromTemplateFile($file, $default = 'not found')
	{
		$config = Zend_Registry::get('config');
		$templatePath = './' . $config->filepath->template . '/' . $this->getTemplate() . '/' . $file;
		$file = @file_get_contents($templatePath);
		if(empty($file))
		{
			$file = $default;
		}
		return $file;
		
	}
	
	public function getLabel($page)
	{
		if(!empty($page->label))
		{
			return $page->label;
		}else{
			return $page->title;
		} 
	}
	
	protected  function logHit($pageId)
	{
	    //we need a new instance of content because this instance is read only
	    $c = new Content();
	    $page = $c->find($pageId)->current();
	    if($page){
    	    $page->hits++;
    	    $page->save();
	    }
	}
}