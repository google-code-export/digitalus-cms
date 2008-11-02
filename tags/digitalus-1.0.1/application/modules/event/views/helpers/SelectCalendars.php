<?php
class Zend_View_Helper_SelectCalendars
{	
    /**
     * values should be an csv list of the ids of the Calendars
     *
     * @param unknown_type $values
     */
	public function SelectCalendars($values)
	{
	    $values = explode(',', $values);
	    
	    $p = new Event_Calendar();
	    $calendars= $p->fetchAll(null, 'title');
	    if($calendars)
	    {
	        foreach ($calendars as $calendar)
	        {
	            if(in_array($calendar->id, $values))
	            {
	                $val = 1;
	            }else{
	                $val = 0;
	            }
	            $checkBoxes[] = $this->view->formCheckbox("calendars[$calendar->id]", $val) . ' <em>' . $calendar->title . "</em>";
	        }
	        if(is_array($checkBoxes))
	        {
	            return "<span class='group'>" . implode('<br />', $checkBoxes) . "</span>";
	        }
	    }
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