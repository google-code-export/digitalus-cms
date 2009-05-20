<?php
class Digitalus_View_Message
{
    private $_ns;
    private $_message;

    public function __construct()
    {

        if (Zend_Registry::isRegistered('message')) {
            $this->_message = Zend_Registry::get('message');
        } else {
            $m = new Zend_Session_Namespace('message');
            if (isset($m->message)) {
                $this->_message = $m->message;
            }
        }
    }

    public function clear()
    {
        unset($this->_message);
        $this->_updateNs();
    }

    public function add($message)
    {
        $this->_message = $message;
        $this->_updateNs();
    }

    public function hasMessage()
    {
        if ($this->_message) {
            return true;
        }
    }

    public function get()
    {
        return $this->_message;
    }

    private function _updateNs()
    {
        $m = new Zend_Session_Namespace('message');
        if (isset($this->_message)) {
            Zend_Registry::set('message',$this->_message);
            $m->message = $this->_message;
        } else {
            unset($m->message);
        }
    }
}