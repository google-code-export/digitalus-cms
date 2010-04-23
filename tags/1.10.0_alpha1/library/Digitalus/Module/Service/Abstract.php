<?php
abstract class Digitalus_Module_Service_Abstract
{
    protected $_response;
    public function __construct ()
    {
        $this->_response = new Digitalus_Module_Service_Response();
    }
    public function getResponse($asXml = false)
    {
        if ($asXml === true) {
            return $this->_response->asXml();
        } else {
            return $this->_response;
        }
    }
}