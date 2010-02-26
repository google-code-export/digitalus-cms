<?php
/**
 * this class is used to differentiate between the front end language and back end
 * this functionality should be revisited
 * @todo refactor this class to use Zend_Translate
 *
 */
class Digitalus_Language
{
    const SESSION_KEY  = 'currentLanguage';
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
            $siteSettings = new Model_SiteSettings;
            $currentLang = $siteSettings->get('default_language');
            if (empty($currentLang)) {
                $config = Zend_Registry::get('config');
                $currentLang = $config->language->defaultLocale;
            }
            if (empty($currentLang)) {
                $locale = new Zend_Locale();
                $currentLang = $locale->getLanguage();
            }
            self::setLanguage($currentLang);
        }
        return $currentLang;
    }

    public static function getAdminLanguage()
    {
        $siteSettings = new Model_SiteSettings;
        $adminLang = $siteSettings->get('admin_language');
        if (empty($adminLang)) {
            $config = Zend_Registry::get('config');
            $adminLang = $config->language->defaultLocale;
        }
        if (empty($adminLang)) {
            $locale = new Zend_Locale();
            $adminLang = $locale->getLanguage();
        }
        if (empty($adminLang)) {
            throw new Digitalus_Language_Exception('No administrator language found!');
        }
        return $adminLang;
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
        return new Zend_Session_Namespace(self::SESSION_KEY);
    }
}