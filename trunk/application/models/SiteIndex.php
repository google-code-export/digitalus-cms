<?php 
class SiteIndex extends Page
{
    protected $_index;
	
	/**
	 * returns the current site index
	 *
	 * @return array
	 */
	public function getIndex($rootId = 0)
	{
		if(null == $this->_index) {
		    $this->_indexPages($rootId);
		}
		return $this->_index;
	}
    
    /**
	 * creates loads the page index
	 * if you pass the optional parentId the index will start with this page
	 * if not it will index the whole site
	 * 
	 * @param integer $parentId
	 */
	private function _indexPages($parentId = 0, $path = null, $pathSeparator = '/')
	{
		if($this->hasChildren($parentId)){
		    $children = $this->getChildren($parentId);
		    foreach ($children as $child) {
		    	//check to see if the child has children
		    	$tmpPath = $path . $child->name;
		
		    	//add the child
		    	$this->_index[$child->id] = $tmpPath;
		    	
		    	$this->_indexPages($child->id, $tmpPath . $pathSeparator, $pathSeparator);		    	
		    }		    
		}
	}
		
}