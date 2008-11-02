<?php
require('./application/modules/event/models/Calendar.php');
require('./application/modules/event/models/Event.php');

class Mod_Event_IndexController extends DSF_Controller_Module_Abstract 
{
    protected $_moduleName = "event";
    protected $_controllerName = "index";
    protected $_modelClass = "Event_Event";
    
    public function indexAction()
    {
        $this->view->events = $this->_model->getUpcomingEvents();
        $calendar = new Event_Calendar();
        $this->view->calendars = $calendar->fetchAll(null, 'title');
    }
    
    public function currentEventsAction()
    {
        
    }
    
    public function listAction()
    {
        $filter = $this->_request->getParam('events');
        $this->view->filter = ucwords($filter) . ' ';
        if($filter == 'expired'){
            $this->view->events = $this->_model->getExpiredEvents();
        }else{
            $this->view->events = $this->_model->getUpcomingEvents();
        }
    }
       
    function beforeOpen()
    {
        $event = $this->_model->find($this->_recordId)->current();
        $ref = $event->reference;
        if(!empty($ref)){
            $arrRef = explode(',', $ref);
            if(in_array('start', $arrRef)){
                $this->view->start_time = $this->getTime($event->publish_date);
            }
            if(in_array('end', $arrRef)){
                $this->view->end_time = $this->getTime($event->archive_date);
            }
            
        }
    }
    
    function afterEdit()
    {
        $this->_model->setCalendars($this->_currentRecord->id, DSF_Filter_Post::raw('calendars'));
    }
    
    function getDate($timestamp)
    {
        $d = new Zend_Date($timestamp);
    	return $d->get('MM-dd-YYYY');    
    }
    
    function getTime($timestamp)
    {
        $d = new Zend_Date($timestamp);
    	return $d->get(Zend_Date::TIME_SHORT);  
    }
}