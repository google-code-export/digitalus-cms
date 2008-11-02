<?php
class DSF_View_Helper_Form_DatePicker
{

	/**
	 * this helper renders a date picker (requires jquery date)
	 * 
	 * @param string $name
	 * @param timestamp $value
	 * 
	 */
	public function DatePicker($name, $value = null){
	    //format the timestamp
	    
	    if($value > 0)
	    {
    	    Zend_Date::setOptions(array('format_type' => 'php'));
    	    $date = new Zend_Date($value);
    		$value = $date->toString('m-d-Y');
	    }else{
	        //we dont want any value that is not a valid date
	        $value = null;
	    }
			
		return $this->view->formText($name, $value, array('class' => 'date-picker'));
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