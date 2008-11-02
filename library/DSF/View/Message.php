<?php
class DSF_View_Message
{
    private $ns;
    private $message;
    
    function __construct()
    {
        
        if(Zend_Registry::isRegistered('message')){
            $this->message = Zend_Registry::get('message');
        }else{
            $m = new Zend_Session_Namespace('message');
            if(isset($m->message)){
                $this->message = $m->message;
            }
        }
    }
    
    function clear()
    {
        unset($this->message);
        $this->updateNs();
    }
    
    function add($message)
    {
        $this->message = $message;
        $this->updateNs();
    }
    
    function hasMessage()
    {
        if($this->message){
            return true;
        }
    }
    
    function get()
    {
        return $this->message;
    }
    
    private function updateNs()
    {
        $m = new Zend_Session_Namespace('message');
        if(isset($this->message)){
            Zend_Registry::set('message',$this->message);
            $m->message = $this->message;
        }else{
            unset($m->message);
        }
    }
}