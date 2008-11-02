<?php
require('./application/modules/event/models/Event.php');
require('./application/modules/event/models/Calendar.php');

class Mod_Event_PublicController extends DSF_Controller_Module_Public
{
    public function upcomingEventsAction()
    {
        $event = new Event_Event();
        $this->view->events = $event->getUpcomingEvents();
        $id = $this->_request->getParam('event');
        if($id > 0){
            $this->view->event = $event->find($id)->current();
            $this->view->showDetail = true;
        }
        
    }
    
    public function eventsCalendarAction()
    {
        $e = new Event_Event();
        $c = new Event_Calendar();
        $calendar = $this->_request->getParam('calendar');
        $eventId = $this->_request->getParam('event', 0);
        if($eventId > 0){
            $this->view->event = $e->find($eventId)->current();
            $this->view->showDetail = true;
        }else{
            $this->view->calendar = $c->find($calendar)->current();
            $this->view->events = $c->getUpcomingEvents($calendar, null);
        }
    }
}