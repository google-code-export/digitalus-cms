<?php
/**
 * this class allows easy querying of complex relationships (grouped many to many, many to one, one to many)
 *
 */
class ReferenceQuery extends Reference 
{
    protected $_operators = array(
        '<' => 'toChild',
        '>' => 'toParent'
        );
    protected $_select;
    protected $_pointer = 2;
    protected $_parentId;
    protected $_rAlias;
    protected $_cAlias;
    
    /**
     * returns a zend rowset of all of the records that are related to the $id
     * the query is a string that uses 2 operators:
     * '>' get children of that type
     * '<' get parents of that type
     * 
     * example:
     * $id = id of a news item
     * $query = "< images > gallery"
     * 
     * this query will return the galleries that contain images that are attached to the news item
     * this might be used for 'other images you may like' or something
     *
     * @param unknown_type $id
     * @param unknown_type $query
     * @return unknown
     */
    public function fetchAll($id, $query, $order = null)
    {
        $this->_select = $this->_db->select();
        
        //set the parent content item (return nothing)
        $this->_select->from(array('c1' => 'content'), array());
        $this->_select->where("c1.id = {$id}");
        if($order !== 'null'){
            $this->_select->order($order);
        }
        
        //clean the query
        $query = DSF_Toolbox_Regex::stripMultipleSpaces($query);
        
        //explode the query
        $queryParts = explode(' ', $query);
        $count = count($queryParts);
        for ($i = 0; $i < $count; $i += 2)
        {
            $action = $queryParts[$i];
            $filter = $queryParts[$i + 1];
            if((($i + 2) == $count) || ($i == 0 && $count == 2))
            {
                $final = true;
                //Zend_Debug::dump('final');
            }else{
                $final = false;
                //Zend_Debug::dump('not final');
                //Zend_Debug::dump($i * 2);
                //Zend_Debug::dump($count);
            }
            if(key_exists($action, $this->_operators))
            {
                //add the join
                $join = $this->_operators[$action];
                $this->$join($filter, $final);
            }
        }

       //$this->_select->__toString();
       $stmt = $this->_select->query();
       
       //reset the pointer
       $this->_pointer = 2;
       return $stmt->fetchAll();
    }
    
    /**
     * @todo combine toChild and toParent
     * adds a join to the select
     * that joins the content item to the children of the type selected
     *
     * @param string $type
     */
    public function toChild($type, $final = false)
    {
        if($final)
        {
            $get = array('*');
        }else{
            $get = array();
        }
        $ref = 'r' . $this->_pointer;
        $con = 'c' . $this->_pointer;
        $parent = 'c' . ($this->_pointer - 1);
        $this->_select->join(array($ref => 'references'), "{$parent}.id = {$ref}.parent_id", array());
        $this->_select->join(array($con => 'content'), "{$ref}.child_id = {$con}.id", $get);
        $this->_select->where("{$con}.content_type = '{$type}'");
        $this->_pointer++;        
    }

    
    /**
     * adds a join to the select
     * that joins the content item to the parent of the type selected
     *
     * @param string $type
     */
    public function toParent($type, $final = false)
    {
        if($final)
        {
            $get = array('*');
        }else{
            $get = array();
        }
        $ref = 'r' . $this->_pointer;
        $con = 'c' . $this->_pointer;
        $parent = 'c' . ($this->_pointer - 1);
        $this->_select->join(array($ref => 'references'), "{$parent}.id = {$ref}.child_id", array());
        $this->_select->join(array($con => 'content'), "{$ref}.parent_id = {$con}.id", $get);
        $this->_select->where("{$con}.content_type = '{$type}'");
        $this->_pointer++;  
        
    }
    
    public function toSQL()
    {
        return $this->_select->__toString();
    }
    
    
    
}