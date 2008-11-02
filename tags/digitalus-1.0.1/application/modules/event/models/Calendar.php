<?php

class Event_Calendar extends Content 
{
    protected $_type = "calendar";
	
    public function getUpcomingEvents($calendarId, $count = 10)
    {
        $where[] = "publish_date > " . time();
        $order = "publish_date ASC";
        return $this->fetchRelatedContent($calendarId, $where, $order, $count);
   }
	
    public function getExpiredEvents($calendarId, $count = 10)
    {
        $where[] = "publish_date <= " . time();
        $order = "publish_date ASC";
        return $this->fetchRelatedContent($calendarId, $where, $order, $count);
   }
}