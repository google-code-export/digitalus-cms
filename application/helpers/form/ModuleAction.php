<?php
class DSF_View_Helper_Form_ModuleAction
{

    /**
     * comments
     */
    public function ModuleAction($stripParams = false)
    {
        $uri = $_SERVER['REQUEST_URI'];
        if ($stripParams && strpos($uri, '/p/')) {
            $parts = explode('/p/', $uri);
            $uri = $parts[0];
        }
        return $uri;
    }
}