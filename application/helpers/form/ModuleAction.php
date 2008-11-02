<?php
class DSF_View_Helper_Form_ModuleAction
{

	/**
	 * comments
	 */
	public function ModuleAction(){
		return $_SERVER['REQUEST_URI'];
	}
}