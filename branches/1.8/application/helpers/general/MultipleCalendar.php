<?php
class Digitalus_View_Helper_General_MultipleCalendar
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
        foreach ($months as $month => $selectedDays) {
            $monthParts = explode('-', $month);
            if (!is_array($selectedDays)) {
                $selectedDays = array();
            }
            $xhtml .= $this->view->Calendar($monthParts[0], $monthParts[1], $selectedDays);
        }
        return $xhtml;
    }

    /**
     * Set this->view object
     *
     * @param  Zend_View_Interface $view
     * @return Zend_View_Helper_DeclareVars
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }
}