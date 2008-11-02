<?php
class DSF_View_Helper_General_MultipleCalendar
{	
    /**
     * renders a set of calendars with links to each day
     * pass this an array of the months with the selected days
     * @param $months = array(numericYear-numericMonth = array(
     *                          numericDay => array('link', 'class', 'content to render on day')
     *                          ));
     *
     */
	public function MultipleCalendar($months = array())
	{
	    $xhtml = null;
        foreach ($months as $month => $selectedDays){
            $monthParts = explode('-', $month);
            if(!is_array($selectedDays)){
                $selectedDays = array();
            }
            $xhtml .= $this->view->Calendar($monthParts[0], $monthParts[1], $selectedDays);
        }
        return $xhtml;
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