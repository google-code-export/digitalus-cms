<?php
class DSF_Module_Block_Loader_Adapter_Xml 
{
	private $_blockData;
	private $_properties;
	
	/**
	 * this function loads the block
	 * from xml
	 * $xml can be passed as a simpleXml element or a XML string
	 *
	 * @param mixed $xml
	 */
	public function load($xml)
	{
		if(is_object($xml)) {
			$this->_blockData = $xml;
		}else{
			$this->_blockData = new SimpleXMLElement($xml);
		}
		
		$this->_setProperties();
	}
	
	private function _setProperties()
	{
		$properties = $this->_blockData->attributes();
		
		$this->_properties = new stdClass();
		
		foreach ($properties as $k => $v) {
			$this->_properties->$k = (string)$v;
		}
	}
	
	public function getProperties()
	{
		return $this->_properties;
	}
}