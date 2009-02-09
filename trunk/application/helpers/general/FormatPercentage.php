<?php
 class DSF_View_Helper_General_FormatPercentage
 {

    /**
     * this helper formats a percentage
     */
    public function FormatPercentage($num)
    {
        return number_format($num, 2) . ' %';
    }
 }
 ?>
