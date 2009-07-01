<?php
class Digitalus_View_Helper_Internationalization_GetCurrentLanguage
{
    /**
     * this helper returns the current language
     *
     * @return unknown
     */
    public function GetCurrentLanguage()
    {
        return Digitalus_Language::getLanguage();
    }
}