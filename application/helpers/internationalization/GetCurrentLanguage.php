<?php
class DSF_View_Helper_Internationalization_GetCurrentLanguage
{
  
    /**
     * this helper returns the current language
     *
     * @return unknown
     */
	public function GetCurrentLanguage($locale = false)
	{
        $config = Zend_Registry::get('config');
	    if(!$locale) {
	        $locale = $config->language->defaultLocale;
	    }
	    return $config->language->translations->$locale;
	    
	}
}
