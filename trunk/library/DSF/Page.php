<?php
class DSF_Page
{
	protected $_id = 0;
	protected $_uri = null;
	protected $_baseUrl = null;
	protected $_params = array();
	protected $_data;
	protected $_meta = array();
	protected $_properties = array();
	protected $_content = array();
	protected $_contentTemplate = null;
	protected $_design;
	protected $_layout;
	public $view;
	
	public function __construct()
	{
		$this->view = new Zend_View();
	}
	
	public function setId($id)
	{
		$this->_id = $id;
	}
	
	public function getId()
	{
		return $this->_id;
	}
	
	public function setUri($uri)
	{
		$this->_uri = $uri;
	}
	
	public function getUri()
	{
		return $this->_uri;
	}
	
	public function setBaseUrl($url)
	{
		$this->_baseUrl = $url;
	}
	
	public function getBaseUrl()
	{
		return $this->_baseUrl;
	}
	
	public function setData($data)
	{
		$this->_data = $data;
	}
	
	public function getData()
	{
		return $this->_data;
	}
	
	public function setParams($params)
	{
		$this->_params = $params;
	}
	
	public function setParam($key, $value)
	{
		$this->_params[$key] = $value;
	}
	
	public function getParams()
	{
		return $this->_params;
	}
	
	public function getParam($key)
	{
		return $this->_params[$key];
	}
	
	public function setMeta($metaData)
	{
		$this->_meta = $metaData;
	}
	
	public function getMeta()
	{
		return $this->_meta;
	}
	
	public function setProperties($properties)
	{
		$this->_properties = $properties;
	}
	
	public function getProperties()
	{
		return $this->_properties;
	}
	
	public function setContent($content)
	{
		$this->_content = $content;
	}
	
	public function getContent($key = null)
	{
	    if($key !== null) {
		    return $this->_content->$key;
	    }else{
		    return $this->_content;   
	    }

	}
	
	public function setContentTemplate($contentTemplate)
	{
		$this->_contentTemplate = $contentTemplate;
	}
	
	public function getContentTemplate()
	{
		return $this->_contentTemplate;
	}
	
	public function setDesign($design)
	{
		$this->_design = $design;
	}
	
	public function getDesign()
	{
		return $this->_design;
	}
	
	public function setLayout($layout)
	{
		$this->_layout = $layout;
	}
	
	public function getLayout()
	{
		return $this->_layout;
	}
	
	public function getView()
	{
		return $this->view;
	}
}