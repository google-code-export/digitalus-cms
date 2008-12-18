<?php
class DSF_View_Helper_Internationalization_GetCurrentLanguage
{
  
    /**
     * this helper returns the current language
     *
     * @return unknown
     */
	public function GetCurrentLanguage()
	{
	    return DSF_Language::getLanguage();
	}
}
