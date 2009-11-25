<?php
class Digitalus_Module_Service_Response
{
    protected $_response;
    public function __construct ()
    {
        $this->_response = new SimpleXMLElement('<response />');
    }
    public function setParam ($param, $value)
    {
        $this->_response->$param = $value;
    }
    public function getParam ($param)
    {
        return (string) $this->_response->$param;
    }
    public function getResponse ()
    {
        return $this->_response;
    }
    public function asXml ()
    {
        return $this->_response->asXml();
    }
}
?>