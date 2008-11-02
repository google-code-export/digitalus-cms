<?php
/**
 * this class uses:
 *  publish_date = event start date
 *  archive_date = event end date
 *  reference = csv list, whether to show the start and end times
 *  
 *
 */
class Event_Event extends Content 
{
    protected $_type = "event";
    
    function before()
	{
	    $startDate = DSF_Filter_Post::get('publish_date');
        $endDate = DSF_Filter_Post::get('archive_date');
        $startTime = DSF_Filter_Post::get('start_time');
        $endTime = DSF_Filter_Post::get('end_time');
        
        //convert them to date objects
        
        if(!empty($startDate)){
         $startDate = new Zend_Date($startDate);
        }

        
        if(!empty($endDate)){
            $endDate = new Zend_Date($endDate);
        }
        
        //add the times if they are set
        if(!empty($startTime)){
            $startDate->add($startTime, Zend_Date::TIMES);
            $ref[] = 'start';
        }
        
        if(!empty($endTime)){
            if(empty($endDate)){
                $endDate = clone($startDate);
            }
            $endDate->add($endTime,Zend_Date::TIMES);
            $ref[] = 'end';
        }
        
        if($ref){
            $this->equalsValue('reference', implode(',', $ref));
        }else{
            $this->equalsValue('reference', null);
        }
        if($startDate){
		  $this->equalsValue('publish_date', $startDate->get(Zend_Date::TIMESTAMP ));
        }

		if($endDate){
		  $this->equalsValue('archive_date', $endDate->get(Zend_Date::TIMESTAMP ));		    
		}

	}
    
 
     /**
     * this sets the calendars for the news item
     * it expects an associative array:
     * calendar_id => value (boolean)
     *
     * @param int $id
     * @param associative array $calendarsArray
     */
    function setCalendars($id, $calendarsArray)
	{
	    //insert calendars array
	    if(is_array($calendarsArray))
	    {
	        foreach ($calendarsArray as $catId => $value)
	        {
	            if($value == 1)
	            {
        	        $this->relate($id, $catId);    	        
	            }else{
        	        $this->unrelate($id, $catId);
	            }
	        }
	    }
	}
	
    public function getUpcomingEvents($count = 10)
    {
        $where[] = "publish_date > " . time();
        $order = "publish_date ASC";
        return $this->fetchAll($where, $order, $count);
    }
    
    public function getExpiredEvents($count = 10)
    {
        $where[] = "publish_date <= " . time();
        $order = "publish_date ASC";
        return $this->fetchAll($where, $order, $count);
    }
}

//1199336400
//1199397600