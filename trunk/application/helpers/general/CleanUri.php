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
     * @param  Zend_this->view_Interface $this->view
     * @return Zend_this->view_Helper_DeclareVars
     */
    public function setview(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }

}