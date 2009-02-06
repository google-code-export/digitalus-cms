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

    public static function setLanguage($language)
    {
        $session = self::getSession();
        $key = self::LANGUAGE_KEY;
        return $session->$key = $language;
    }

    public static function getLanguage()
    {
        $session = self::getSession();
        $key = self::LANGUAGE_KEY;
        $currentLang = $session->$key;
        if (empty($currentLang)) {
            $config = Zend_Registry::get('config');
            $locale = $config->language->defaultLocale;
            $currentLang = $locale;
        }
        return $currentLang;
    }

    public static function getFullName($locale)
    {
        $config = Zend_Registry::get('config');
        $translations = $config->language->translations->toArray();
        if (isset($translations[$locale])) {
            return $translations[$locale];
        }
        return null;
    }

    public static function getSession()
    {
        return new Zend_Session_Namespace('currentLanguage');
    }
}

?>