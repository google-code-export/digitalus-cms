<?php

class Properties extends Xml  
{
	protected $_namespace = "properties";
	
	public function set($data, $pageId)
	{
		$xml = new SimpleXMLElement("<properties />");
		
		if(is_array($data)) {
			foreach ($data as $key => $value) {
				$xml->$key = strip_tags($value);
			}
		}
		
		$this->saveXml($this->_getKey($pageId), $xml);
	}
	
	public function asArray($pageId)
	{
		$xml = $this->get($pageId);
		if($xml){
			foreach ($xml as $key => $value) {
				$data[$key] = $value;
			}
			if(is_array($data)) {
				return $data;
			}
		}
	}
	
	public function get($pageId)
	{
		return $this->open($this->_getKey($pageId));
	}
	
	protected  function _getKey($pageId)
	{
		return $this->_namespace . '_' . $pageId;
	}
}