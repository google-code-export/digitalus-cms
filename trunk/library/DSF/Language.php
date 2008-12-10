<?php

/**
 * this class is used to differentiate between the front end language and back end
 * this functionality should be revisited
 * @todo refactor this class to use Zend_Translate
 *
 */
class DSF_Language {
    const SESSION_KEY = 'currentLanguage';
    const LANGUAGE_KEY = 'current';

    static function setLanguage($language)
    {
        $session = self::getSession();
        $key = self::LANGUAGE_KEY;
        return $session->$key = $language;
    }
    
    static function getLanguage()
    {
        $session = self::getSession();
        $key = self::LANGUAGE_KEY;
        $currentLang = $session->$key;
        if(empty($currentLang)) {
            $config = Zend_Registry::get('config');
            $locale = $config->language->defaultLocale;
    	    $currentLang = $config->language->translations->$locale;
        }
        return $currentLang;        
    }
    
    static function getSession()
    {
        return new Zend_Session_Namespace('currentLanguage');
    }
}

?>