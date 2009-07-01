<?php
class DSF_View_Helper_Cms_RenderModule
{
    /**
     * render a module page like news_showNewPosts
     */
    public function RenderModule($moduleData, $defaultModule = null, $params = array())
    {
        if (!empty($moduleData) || $defaultModule != null) {
            if (!empty($moduleData)) {
                $xml = simplexml_load_string($moduleData);
            }

            if ($xml->module == 0 && $defaultModule != null) {
                $xml = simplexml_load_string($defaultModule);
            }
            if(is_object($xml)) {
                //build params
                foreach ($xml as $k => $v) {
                    $params[$k] = (string)$v;
                }
                $moduleParts = explode('_', $xml->module);

                if (is_array($moduleParts) && count($moduleParts) == 2) {
                    $name = $moduleParts[0];
                    $action = $moduleParts[1];
                    return $this->view->LoadModule($name, $action, $params);
                }
            }

        } else {
            return null;
        }
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