<?php
class DSF_View_Helper_Internationalization_GetTranslation
{

    /**
     * this helper returns the translation for the passed key
     * it will optionally add the controller
     * and action to the key
     *
     * example: controller_action_page_title
     *
     * @return unknown
     */
    public function GetTranslation($key, $locale = null, $viewInstance = null)
    {
        if ($viewInstance !== null) {
            $this->setview($viewInstance);
        }
        if ($locale == null) {
            $locale = $this->view->GetCurrentLanguage();
        }
        $this->view->translate()->setLocale($locale);

        return $this->view->translate($key);
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
