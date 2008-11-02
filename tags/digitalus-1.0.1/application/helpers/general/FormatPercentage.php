<?php
class DSF_View_Helper_General_FormatPercentage
{

	/**
	 * comments
	 */
	public function FormatPercentage($num){
		return number_format($num,2) . " %";
	}
}