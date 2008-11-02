<?php
require('./application/modules/event/models/Calendar.php');

class Mod_Event_CalendarController extends DSF_Controller_Module_Abstract 
{
    protected $_moduleName = "event";
    protected $_controllerName = "calendar";
    protected $_modelClass = "Event_Calendar";
}