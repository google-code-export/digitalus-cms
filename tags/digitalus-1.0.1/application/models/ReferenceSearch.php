<?php
/**
 * this class allows easy querying of complex relationships (grouped many to many, many to one, one to many)
 *      $search = new ReferenceSearch();
        $search->search('resource', 'test');
        $search->addFilter('state', '<', array('title' => 'Massachusetts'), array('id','title'));
        Zend_Debug::dump($search->getResults('title', 20, 1));
 */
class ReferenceSearch extends Reference 
{
    protected $_select;
    protected $_pointer = 2;
    protected $_parentId;
    protected $_rAlias;
    protected $_cAlias;
    
    public function search($resultType, $keywords = null, $params = null)
    {
        $this->_select = $this->_db->select();
        $this->_select->from(array('c1' => 'content'), array('*'));
        $params['content_type'] = $resultType;
        $this->addWhere('c1', $params);
        
        //add fulltext keyword search
        if(!empty($keywords) && $keywords !== null)
        {
            $keywords = DSF_Toolbox_Regex::stripMultipleSpaces($keywords);
            $keywords = str_replace(' ',',', $keywords);
            
            $this->_select->where("MATCH (c1.`title`, c1.`content`, c1.`tags`) AGAINST ('{$keywords}')");
        }
    }
    
    public function addFilter($type, $action = '>', $params = array(), $fields = array())
    {
        $newAlias = $this->addJoin($action, $fields);
        $params['content_type'] = $type;
        $this->addWhere($newAlias, $params);
    }
    
    
    
    private function addWhere($alias, $params = null)
    {
        if(is_array($params))
        {
            foreach ($params as $k=>$v)
            {
                $this->_select->where($this->_db->quoteInto("{$alias}.{$k} = ?", $v));
            }
        }
    }
    
    public function getResults($order = null, $limit = null, $page = null)
    {
        if($order !== null)
        {
            $this->_select->order(array('c1.' . $order));
        }
        
        if($limit !== null && $page !== null)
        {
            $this->_select->limitPage($page, $limit);
        }elseif($limit !== null){
            $this->_select->limit($limit);
        }
        $stmt = $this->_db->query($this->_select);
        return $stmt->fetchAll();
    }
 
    /**
     * returns the alias of the content table
     */
    private function addJoin($direction, $fields)
    {
        $ref = 'r' . $this->_pointer;
        $con = 'c' . $this->_pointer;
        if($direction == '>') //parents of
        {
            $this->_select->join(array($ref => 'references'), "c1.id = {$ref}.child_id", array());
            $this->_select->join(array($con => 'content'), "{$ref}.parent_id = {$con}.id", $fields);  
        }else{ //default to children of
            $this->_select->join(array($ref => 'references'), "c1.id = {$ref}.parent_id", array());
            $this->_select->join(array($con => 'content'), "{$ref}.child_id = {$con}.id", $fields);            
        }
        $this->_pointer++;   
        return $con; 
    }

    public function toSQL()
    {
        return $this->_select->__toString();
    }
    
    
    
}