<?php
class Zend_View_Helper_RenderEventDate
{	
    /**
     * values should be an csv list of the ids of the Categories
     *
     * @param unknown_type $values
     */
	public function RenderEventDate($event, $dateFormat = "MMM d, yyyy")
	{

	    //convert them to date objects
        $startDate = new Zend_Date($event->publish_date);
        
        if(!empty($event->archive_date)){
            $endDate = new Zend_Date($event->archive_date);
        }
        
        $ref = $event->reference;
        if(!empty($ref)){
            $arrRef = explode(',', $ref);
            if(in_array('start', $arrRef)){
                $start_time = $startDate->get(Zend_Date::TIME_SHORT);
            }
            if(in_array('end', $arrRef)){
                $end_time = $endDate->get(Zend_Date::TIME_SHORT);
            }
        }
        
        $dateString = "<span class='date'>" . $startDate->get($dateFormat) . "</span>";
        if($start_time){
            $dateString .= " <span class='time'>" . $start_time . "</span>";
        }
        
        if($endDate || $end_time){
            $dateString .= " <em>to</em> ";
        }
        
        if($endDate && ($startDate->get($dateFormat) != $endDate->get($dateFormat))){
            $dateString .= "<span class='date'>" . $endDate->get($dateFormat) . "</span>";
        }
        
        if($end_time){
            $dateString .= " <span class='time'>" . $end_time . "</span>";
        }
        
        return $dateString;
	}
	
    /**
     * Set this->view object
     *
     * @param  Zend_this->view_Interface $this->view
     * @return Zend_this->view_Helper_DeclareVars
     */
    public function setview(Zend_view_Interface $view)
    {
        $this->view = $view;
        return $this;
    }
}