<?php
class Model_Reference extends Zend_Db_Table
{
    protected $_name = 'references';

    /**
     * validates the relationship, then inserts it
     *
     * @param integer $parentId
     * @param integer $childId
     * @return unknown
     */
    public function addChild($parentId, $childId)
    {
        //you cant create duplicate relationships
        $where[] = 'parent_id = ' . $parentId;
        $where[] = 'child_id = ' . $childId;

        if (!$this->fetchRow($where)) {
            $data['parent_id'] = $parentId;
            $data['child_id'] = $childId;
            return $this->insert($data);
        }
    }

    /**
     * add multiple children at once by passing this an array of the childrens' ids
     *
     * @param integer $parentId
     * @param array $children
     */
    public function addChildren($parentId, $children)
    {
        if (is_array($children)) {
            foreach ($children as $child) {
                $this->addChild($parentId, $child);
            }
        }
    }

    /**
     * removes a child reference
     *
     * @param integer $parentId
     * @param integer $childId
     */
    public function removeChild($parentId, $childId)
    {
        $where[] = 'parent_id = ' . $parentId;
        $where[] = 'child_id = ' . $childId;
        return $this->delete($where);
    }

    /**
     * removes all of the children from the specified record
     *
     * @param integer $parentId
     * @param string $type
     * @return unknown
     */
    public function removeChildren($parentId, $type = false)
    {
        //get the children
        $sql = "SELECT
            `references`.id
            FROM
            content
            Inner Join `references` ON `references`.child_id = content.id
            WHERE
            `references`.parent_id =  {$parentId}";

        if ($type) {
            $sql .= " AND content.content_type =  '{$type}'";
        }

        $children = $this->_db->fetchAll($sql);
        if ($children) {
            foreach ($children as $child) {
               $this->delete('id = ' . $child->id);
            }
        }
    }

    /**
     * returns a Zend rowset of all of the content items that belong to the current record
     * if you set $childContentType it will only return that type of children (eg; image, news, etc)
     *
     * @param integer $contentId
     * @param string $childContentType
     * @return unknown
     */
    public function getChildren($parentId, $childContentType = null, $order = null, $limit = null)
    {
        $where[] = 'parent_id = ' . $parentId;
        $children = $this->fetchAll($where);
        unset($where);
        foreach ($children as $child) {
            $ids[] = $child->child_id;
        }
        if (is_array($ids)) {
            $idList = implode(',', $ids);
            if ($childContentType !== null) {
                $where[] = $this->_db->quoteInto('content_type = ?', $childContentType);
            }
            $where[] = 'id IN (' . $idList . ')';
            $c = new Content();
            return $c->fetchAll($where, $order, $limit);
        }
    }

}