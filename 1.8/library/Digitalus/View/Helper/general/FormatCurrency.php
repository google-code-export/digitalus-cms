<?php
class Digitalus_View_Helper_General_FormatCurrency
{

    /**
     * comments
     */
    public function FormatCurrency($num)
    {
        return '$' . number_format($num, 2);
    }
}