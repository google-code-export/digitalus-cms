<?php
class ContentNode extends DSF_Db_Table 
{
    protected $_name = "content_nodes"; 
    protected $_namespace = "page";   
    
    /**
     * returns the selected content block
     *
     * @param int $id
     * @param string $node
     * @param string $version
     */
	public function fetchContent($id, $node, $version = null) {
	    $where[] = $this->_db->quoteInto("parent_id = ?", $this->_namespace . '_' . $id);
	    $where[] = $this->_db->quoteInto("node = ?", $node);
	    if($version != null)  {
	        $where[] = $this->_db->quoteInto("version = ?", $version);
	    }else{
	        $where[] = "version IS NULL";
	    }
	    
	    $row = $this->fetchRow($where);
	    if($row && !empty($row->content))  {
	        return stripslashes($row->content);
	    }
	    
	    return false;
	    
	}
	
	/**
	 * returns the content object for the selected page
	 * if nodes is set then it will only return the specified nodes
	 * otherwise it returns all
	 *
	 * @param int $pageId
	 * @param array $nodes
	 * @return object
	 */
	public function fetchContentObject($pageId, $nodes = null, $namespace = null, $version = null) {
		if(null == $namespace) {
			$namespace = $this->_namespace;
		}
	    $data = new stdClass();
	    
	    $where[] = $this->_db->quoteInto("parent_id = ?", $namespace . '_' . $pageId);
	    if($version != null){
	        $where[] = $this->_db->quoteInto("version = ?", $version);
	    }
	    
	    $rowset = $this->fetchAll($where);
	    
	    if($rowset->count() > 0) {
    	        foreach ($rowset as $row) {
    	            $node = $row->node;
    	            $data->$node = stripslashes($row->content);
    	        }
	    }
        if(is_array($nodes))  {
            $return = new stdClass();
            foreach ($nodes as $node) {
	            if(!empty($data->$node)) {
                   $return->$node = $data->$node;
	            }else{
	               $return->$node = null; 
	            }
            }
            return $return;
        }else{  
            return $data;
        }	       
	}
	
	public function fetchContentArray($pageId, $nodes = null, $namespace = null, $version = null)
	{
		$dataArray = array();
		$data = $this->fetchContentObject($pageId, $nodes, $namespace, $version);
		if($data) {
			foreach ($data as $k => $v) {
				$dataArray[$k] = $v;
			}
			return $dataArray;
		}else{
			return null;
		}
	}
	
	public function getVersions($parentId)
	{
	    $select = $this->select();
	    $select->distinct(true);
	    $select->where("parent_id = ?", $parentId);
	    $result = $this->fetchAll($select);
	    if($result) {
            $config = Zend_Registry::get('config');
            $siteVersions = $config->language->translations;
	        $versions = array();
	        foreach($result as $row) {
	            $v = $row->version;
	            $versions[$v] = $siteVersions->$v;
	        }
	        return $versions;
	    }
	    return null;
	}
	
	/**
	 * this function sets a content node
	 * if the node already exists then it updates it
	 * if not then it inserts it
	 *
	 * @param int $pageId
	 * @param string $node
	 * @param string $content
	 * @param string $version
	 */
	public function set($pageId, $node, $content, $version = null) {
	    $node = strtolower($node);
	    
	    $where[] = $this->_db->quoteInto("parent_id = ?", $this->_namespace . '_' . $pageId);
	    $where[] = $this->_db->quoteInto("node = ?", $node);
	    if($version != null)  {
	       $where[] = $this->_db->quoteInto("version = ?", $version);
	    }

	    $row = $this->fetchRow($where);
	      
	      
	    if($row)  {
	        $row->content = $content;
	        $row->save();
	    }else{
	        $data = array(
	           'parent_id'       => $this->_namespace . '_' . $pageId,
	           'node'           => $node,
	           'content'       => $content
	        );
	        if($version != null)  {
	            $data['version'] = $version;
	        }
	        $this->insert($data);
	    }
	}
}