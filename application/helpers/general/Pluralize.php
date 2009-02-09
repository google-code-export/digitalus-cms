<?php
class DSF_View_Helper_General_Pluralize
{

    /**
     * comments
     */
    public function Pluralize($string, $count)
    {
        if ($count > 1){
            //get the last letter
            //this wont be perfect, but well try to pluralize things right
            $lastChar = substr($string, strlen($string) - 1, 1);
            if ($lastChar == 'y') {
                //remove the y
                $string = substr($string, 0, strlen($string) - 1);
                $string .= 'ies';
            } else {
                $string .= 's';
            }
        }
        return $string;
    }
}