<?php


/**
 * DSF CMS
 * 
 * DESCRIPTION
 * this class manages related content
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://digitalus-media.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@digitalus-media.com so we can send you a copy immediately.
 *
 * @category   DSF CMS
 * @package    DSF_CMS_Models
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: RelatedContent.php Mon Dec 24 20:23:01 EST 2007 20:23:01 forrest lyman $
 */

class RelatedContent extends Content 
{
    /**
     * returns a zend rowsest 
     * you pass it a rowset of the rows that you want to use
     * you can optionaly pass where clauses, set the order, and / or limit
     *
     * @param zend_db_rowset $rowset
     * @param array $where
     * @param array $order
     * @param integer $limit
     * @return zend_db_rowset
     */
    public function fetchRelated($rowset, $where = null, $order = null, $limit = null)
    {
        $ids = array();
        if(count($rowset) > 0)
        {
            foreach ($rowset as $row)
            {
                if(!empty($row->related_content))
                {
                	//add the related content ids to the ids array
                    $ids = array_merge($ids, explode(',', $row->related_content));
                }
            }
            if(count($ids) > 0)
            {
                $cleanList = implode(',', array_unique($ids));
                $where[] = "id IN (" . $cleanList . ")";
                return parent::fetchAll($where, $order, $limit);
            }
        }
       
    }
    
    /**
     * relate two content items
     *
     * @param int $id
     * @param int $relatedId
     */
    public function relate($id, $relatedId)
    {
        $this->addRelation($id, $relatedId);
        $this->addRelation($relatedId, $id);
    }
    
    /**
     * removes the relationship between two items
     *
     * @param int $id
     * @param int $relatedId
     */
    function unrelate($id, $relatedId)
    {
        $this->removeRelation($id, $relatedId);
        $this->removeRelation($relatedId, $id);
    }
    
    /**
     * performs the queries to add the relationship
     *
     * @param int $id
     * @param int $relatedId
     */
    private function addRelation($id, $relatedId)
    {
        $row = $this->find($id)->current();
        if($row)
        {
            $rel = $row->related_content;
            if(!empty($rel))
            {
                $arr = explode(',', $rel);
            }
            $arr[] = $relatedId;
            $row->related_content = implode(',', array_unique($arr));
            $row->save();
        }
    }
    
    /**
     * performs the query to remove a relationship
     *
     * @param int $id
     * @param int $relatedId
     */
    private function removeRelation($id, $relatedId)
    {
        $row = $this->find($id)->current();
        if($row)
        {
            $rel = $row->related_content;
            $arr = explode(',', $rel);
            $index = array_search($relatedId, $arr);
            if($index > 0 || $index === 0)
            {
                unset($arr[$index]);
                $row->related_content = implode(',', array_unique($arr));
                $row->save(); 
            }            
        }

    }
    
    /**
     * helper function which returns the related content field by content id
     *
     * @param int $id
     * @return string
     */
    private function getRelatedContentById($id)
    {
        $row = $this->find($id)->current();
        return $row->related_content;
    }
}