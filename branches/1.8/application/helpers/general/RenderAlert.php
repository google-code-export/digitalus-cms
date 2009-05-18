<?php
class DSF_View_Helper_General_RenderAlert
{

    /**
     * comments
     */
    public function RenderAlert()
    {
        $m = new DSF_View_Message();
        $ve = new DSF_View_Error();
        $alert = false;
        $message = null;
        $verror = null;

        $alert = null;

        if ($ve->hasErrors()) {
            $verror = '<p>'. $this->view->getTranslation('The following errors have occurred') . ':</p>' . $this->view->HtmlList($ve->get());
            $alert .= '<fieldset><legend>'. $this->view->getTranslation('Errors') . '</legend>' . $verror . '</fieldset>';
        }

        if ($m->hasMessage()) {
            $message .= '<p>' . $m->get() . '</p>';
            $alert   .= '<fieldset><legend>'. $this->view->getTranslation('Message') . '</legend>' . $message . '</fieldset>';
        }

        //after this renders it clears the errors and messages
        $m->clear();
        $ve->clear();

        return $alert;
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