<?php
class DSF_View_Helper_General_CleanUri
{
    /**
     * removes any params from the uri
     */
    public function CleanUri($uri = null, $absolute = false, $stripUnderscores = false)
    {
        if ($uri == null) {
           $uri = $this->view->pageObj->getCleanUri();
        }
        if ($absolute && !empty($uri)) {
            $uri = '/' . $uri;
        }

        if ($stripUnderscores) {
            $uri = DSF_Toolbox_String::stripUnderscores($uri, true);
        }
        return  DSF_Toolbox_String::addHyphens($uri);
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