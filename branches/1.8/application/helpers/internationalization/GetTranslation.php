<?php
class Digitalus_View_Helper_Internationalization_GetTranslation
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
    public function getTranslation($key, $locale = null, $viewInstance = null)
    {
        if ($viewInstance !== null) {
            $this->setview($viewInstance);
        }
        $adapter = Zend_Registry::get('Zend_Translate');
        $moduleName = $this->view->getRequest()->getModuleName();
        $currentLanguage = $this->view->GetCurrentLanguage();
        if ($locale != null) {
            $this->view->translate()->setLocale($locale);
        } elseif ($moduleName != 'admin' && $adapter->isAvailable($currentLanguage)) {
            $this->view->translate()->setLocale($currentLanguage);
        }
        return $this->view->translate($key);
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
