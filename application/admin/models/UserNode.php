<?php
class Model_UserNode extends Digitalus_Db_Table
{
#    protected $_name = 'user_nodes';

    /**
     * returns the selected content block
     *
     * @param string $userName
     * @param string $version
     */
    public function fetchContent($userName)
    {
        $where[] = $this->_db->quoteInto('user_name = ?', $userName);

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
     * @param string $userName
     * @return object
     */
    public function fetchContentObject($userName)
    {
        $data = new stdClass();

        $where[] = $this->_db->quoteInto('user_name = ?', $userName);

        $rowset = $this->fetchAll($where);
        if ($rowset->count() > 0) {
            foreach ($rowset as $row) {
                $label = $row->label;
                $data->$label = stripslashes($row->content);
            }
        }
        return $data;
    }

    public function fetchContentArray($userName)
    {
        $dataArray = array();
        $data = $this->fetchContentObject($userName);
        if ($data) {
            foreach ($data as $k => $v) {
                $dataArray[$k] = $v;
            }
            return $dataArray;
        }
        return null;
    }

    public function getVersions($userName)
    {
        $select = $this->select();
        $select->distinct(true);
        $select->where('user_name = ?', $userName);
        $result = $this->fetchAll($select);
        if ($result) {
            $config = Zend_Registry::get('config');
            $siteVersions = $config->language->translations;
            $versions = array();
            foreach ($result as $row) {
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
     * @param string $userName
     * @param string $content
     * @param string $version
     */
    public function set($userName, $content)
    {
        $where[] = $this->_db->quoteInto('user_name = ?', $userName);

        $row = $this->fetchRow($where);

        if ($row) {
            $row->content = $content;
            $row->save();
        } else {
            $data = array(
               'user_name' => $userName,
               'content'   => $content
            );
            $this->insert($data);
        }
    }
}