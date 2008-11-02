<?php
class Design extends Zend_Db_Table 
{
	protected $_name = "designs";
	protected $_design = null;
	
	public function createDesign($name, $notes)
	{
		$row = $this->createRow();
		$row->name = $name;
		$row->notes = $notes;
		$row->save();
		
		return $this->_db->lastInsertId();
	}
	
	public function updateDesign($id, $name, $notes, $layout, $stylesheets, $inlineStyles)
	{
		$row = $this->find($id)->current();
		$row->name = $name;
		$row->notes = $notes;
		$row->layout = $layout;
		$row->styles =	serialize($stylesheets);
		$row->inline_styles = $inlineStyles;
		return $row->save();
	}
	
	public function deleteDesign($id)
	{
		$row = $this->find($id)->current();
		if($row){
			$row->delete();
		}
	}
	
	public function listDesigns()
	{
		$select = $this->select();
		$select->order('name');
		return $this->fetchAll($select);
	}
	
	public function getDesign($designId)
	{
		return $this->_design;
	}
	
	public function getValue($key)
	{
		if(isset($this->_design->$key)) {
			return $this->_design->$key;
		}
	}
		
	public function getLayout()
	{
		return $this->_design->layout;
	}
	
	public function setDesign($designId)
	{
		$this->_design = $this->find($designId)->current();
	}
	
	public function getStylesheets()
	{
		if(!empty($this->_design->styles)) {
			return unserialize($this->_design->styles);
		}
	}
	
	public function getInlineStyles()
	{
		if(!empty($this->_design->inline_styles)) {
			return unserialize($this->_design->inline_styles);
		}
	}
	
	/**
	 * this wont be implemented in 1.5
	 *
	 * @return unknown
	 */
	public function getScripts()
	{
		if(!empty($this->_design->scripts)) {
			$scripts = simplexml_load_string($this->_design->scripts);
			foreach ($scripts as $script){
				$scriptsArray[] = (string)$script;
			}
			if(is_array($scriptsArray)) {
				return $scriptsArray;
			}
		}
		
	}
	
	
	/**
	 * this wont be implemented in 1.5
	 *
	 * @return unknown
	 */
	public function getPlaceholders()
	{
		if(!empty($this->_design->placeholders)) {
			$placeholders = simplexml_load_string($this->_design->placeholders);
			foreach ($placeholders as $placeholder){
				$placeholdersArray[] = (string)$placeholder;
			}
			if(is_array($placeholdersArray)) {
				return $placeholdersArray;
			}
		}
	}
}