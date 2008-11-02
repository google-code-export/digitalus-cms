<?php

class NewsItem extends Content 
{
    protected $_type = 'newsItem';
    
    function getRecent($category = null, $limit = 10)
    {
        if($category !== null){
            $where[] = $category . " IN (related_content)";
        }
        return $this->fetchAll($where, 'create_date', $limit);
    }
    
    function search($keywords, $searchAll = 0, $order = "publish_date DESC", $limit = 20)
    {
        if($searchAll === 0 || empty($searchAll))
        {
    	    $where[] = $this->_db->quoteInto("publish_date <= ?", time());
    	    $where[] = $this->_db->quoteInto("archive_date >= ?", time());
        }
        return parent::search($keywords, $where, $order, $limit);
    }
    
    function getCurrent($categoryId = null)
    {
        if($category !== null){
            return $this->getItemsByCategory($categoryId);
        }else{
    	    $where[] = "(publish_date <= " . time() . " OR publish_date IS NULL or publish_date = 0)";
    	    $where[] = "(archive_date >= " . time() . " OR archive_date IS NULL or archive_date = 0)";
    	    return $this->fetchAll($where);
        }
    }
    
    /**
     * this sets the categories for the news item
     * it expects an associative array:
     * category_id => value (boolean)
     *
     * @param int $id
     * @param associative array $categoriesArray
     */
    function setCategories($id, $categoriesArray)
	{
	    //insert categories array
	    if(is_array($categoriesArray))
	    {
	        foreach ($categoriesArray as $catId => $value)
	        {
	            if($value == 1)
	            {
        	        $this->relate($id, $catId);       	        
	            }else{
        	        $this->unrelate($id, $catId);
	            }
	        }
	    }
	}
	
	function getItemsByCategory($categoryId)
	{
	    $c = new NewsCategory();
	    $cat = $c->find($categoryId);
	    $r = new RelatedContent();
	    $where[] = $this->_db->quoteInto("content_type = ?", $this->_type);
	    
	    //if the publish date is set then this will only return items after the publish date
	    $where[] = "(" . $this->_db->quoteInto("publish_date <= ?", time()) . ") OR (publish_date IS NULL) OR (publish_date = 0)";
	    
	    //if archive date is set then this will onl return items that are not archived
	    $where[] = "(" . $this->_db->quoteInto("archive_date >= ?", time()) . ") OR (archive_date IS NULL) OR (archive_date = 0)";

	    return $r->fetchRelated($cat, $where, 'publish_date');
	}
}