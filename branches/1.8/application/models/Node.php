<?php
class ContentNode extends DSF_Db_Table
{
    protected $_name = 'content_nodes';
    const TEST = 'value';

    /**
     * returns the selected content block
     *
     * @param int $id
     * @param string $node
     * @param string $version
     */
    public function fetchContent($id, $node, $version = null)
    {
        $where[] = $this->_db->quoteInto('parent_id = ?', $id);
        $where[] = $this->_db->quoteInto('node = ?', $node);
        if ($version != null) {
            $where[] = $this->_db->quoteInto('version = ?', $version);
        }

        $row = $this->fetchRow($where);
        if ($row && !empty($row->content)) {
            return stripslashes($row->contet);
        }

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
    public function fetchContentObject($pageId, $nodes = null)
    {
        $data = new stdClass();

        $where[] = $this->_db->quoteInto('parent_id = ?', $pageId);
        $rowset = $this->fetchAll($where);

        if ($rowset->count() > 0) {
                foreach ($rowset as $row) {
                    $node = $row->node;
                    $data->$node = stripslashes($row->content);
                }
        }
        if (is_array($nodes))  {
            $return = new stdClass();
            foreach ($nodes as $node) {
                if (!empty($data->$node)) {
                   $return->$node = $data->$node;
                } else {
                   $return->$node = null;
                }
            }
            return $return;
        } else {
            return $data;
        }
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
    public function set($pageId, $node, $content, $type = 'content', $version = null)
    {
        $node = strtolower($node);

        $where[] = $this->_db->quoteInto('parent_id = ?', $pageId);
        $where[] = $this->_db->quoteInto('node = ?', $node);
        if ($version != null)  {
           $where[] = $this->_db->quoteInto('version = ?', $version);
        }

        $row = $this->fetchRow($where);


        if ($row)  {
            $row->content = $content;
            $row->save();
        } else {
            $data = array(
               'parent_id'       => $pageId,
               'node'           => $node,
               'content'       => $content
            );
            if ($version != null)  {
                $data['version'] = $version;
            }
            $this->insert($data);
        }
    }
}