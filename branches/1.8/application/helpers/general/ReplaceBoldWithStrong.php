<?php
class Digitalus_View_Helper_General_ReplaceBoldWithStrong
{

    public function ReplaceBoldWithStrong($content, $strongClass = null)
    {
        if ($strongClass) {
            $class = 'class="' . $strongClass . '"';
        }

        //get the content body
        $content = Digitalus_Toolbox_Regex::extractHtmlPart($content, 'body');

        //replace the tags
        $content = Digitalus_Toolbox_Regex::replaceTag('b', 'strong', $content, $class) ;
        return $content;
    }
}