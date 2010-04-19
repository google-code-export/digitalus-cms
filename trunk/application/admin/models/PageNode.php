<?php
class Model_PageNode extends Digitalus_Db_Table
{
    protected $_name = 'page_nodes';

    /**
     * returns the selected content block
     *
     * @param int $pageId
     * @param string $nodeType
     * @param string $language
     */
    public function fetchContent($pageId, $nodeType, $language = null)
    {
        $where[] = $this->_db->quoteInto('page_id = ?', $pageId);
        $where[] = $this->_db->quoteInto('node_type = ?', $nodeType);
        if ($language != null) {
            $where[] = $this->_db->quoteInto('language = ?', $language);
        } else {
            $where[] = 'language IS NULL';
        }

        $row = $this->fetchRow($where);
        if ($row && !empty($row->content)) {
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
     * @param array $nodeTypes
     * @param string $language
     * @return object
     */
    public function fetchContentObject($pageId, $nodeTypes = null, $language = null)
    {
        $data = new stdClass();

        $where[] = $this->_db->quoteInto('page_id = ?', $pageId);
        if ($language != null) {
            $where[] = $this->_db->quoteInto('language = ?', $language);
        }

        $rowset = $this->fetchAll($where);
        if ($rowset->count() > 0) {
            foreach ($rowset as $row) {
                $nodeType = $row->node_type;
                $data->label     = stripslashes($row->label);
                $data->headline  = stripslashes($row->headline);
                $data->$nodeType = stripslashes($row->content);
            }
        }
        if (is_array($nodeTypes)) {
            $return = new stdClass();
            foreach ($nodeTypes as $nodeType) {
                if (!empty($data->$nodeType)) {
                   $return->$nodeType = $data->$nodeType;
                } else {
                   $return->$nodeType = null;
                }
            }
            return $return;
        }
        return $data;
    }

    /**
     * returns the content array for the selected page
     * if nodes is set then it will only return the specified nodes
     * otherwise it returns all
     *
     * @param int $pageId
     * @param array $nodeTypes
     * @param string $language
     * @return null|array
     */
    public function fetchContentArray($pageId, $nodeTypes = null, $language = null)
    {
        $dataArray = array();
        $data = $this->fetchContentObject($pageId, $nodeTypes, $language);
        if ($data) {
            foreach ($data as $k => $v) {
                $dataArray[$k] = $v;
            }
            return $dataArray;
        }
        return null;
    }

    /**
     * returns page languages
     *
     * @param int $pageId
     * @return null|array
     */
    public function getVersions($pageId)
    {
        $select = $this->select();
        $select->distinct(true)
               ->where('page_id = ?', $pageId);
        $result = $this->fetchAll($select);
        if ($result) {
            $config = Zend_Registry::get('config');
            $siteVersions = $config->language->translations;
            $languages = array();
            foreach ($result as $row) {
                $v = $row->language;
                $languages[$v] = $siteVersions->$v;
            }
            return $languages;
        }
        return null;
    }

    /**
     * this function sets a content node
     * if the node already exists then it updates it
     * if not then it inserts it
     *
     * @param int $pageId
     * @param string $nodeType
     * @param string $content
     * @param string $language
     * @param string $label
     * @param string $headline
     */
    public function set($pageId, $nodeType, $content, $language = 'en', $label = null, $headline = null)
    {
        $nodeType = strtolower($nodeType);

        $where[] = $this->_db->quoteInto('page_id = ?', $pageId);
        $where[] = $this->_db->quoteInto('node_type = ?', $nodeType);
        if ($language != null) {
           $where[] = $this->_db->quoteInto('language = ?', $language);
        }

        $row = $this->fetchRow($where);
        if ($row) {
            $row->label    = $label;
            $row->headline = $headline;
            $row->content  = $content;
            $row->save();
        } else {
            $data = array(
               'page_id'   => $pageId,
               'node_type' => $nodeType,
               'content'   => $content,
               'language'  => $language,
               'label'     => $label,
               'headline'  => $headline
            );
            $this->insert($data);
        }
    }
}