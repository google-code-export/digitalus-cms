<?php
class DSF_View_Helper_General_RenderAlert
{

	/**
	 * comments
	 */
	public function RenderAlert(){
        $m = new DSF_View_Message();
        $ve = new DSF_View_Error();
        $alert = false;
        
        if($m->hasMessage() || $ve->hasErrors()){
            if($m->hasMessage()){
                $message .= "<p>" . $m->get() . "</p>";
            }
            
            if($ve->hasErrors()){
                $verror = "<p>The following errors have occurred:</p>" . $this->view->HtmlList($ve->get());
            }
            $alert = "<div class='message_box'>" . $message . $verror . "</div>";
        }
        
        //after this renders it clears the errors and messages
        $m->clear();
        $ve->clear();
        
		return $alert;
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