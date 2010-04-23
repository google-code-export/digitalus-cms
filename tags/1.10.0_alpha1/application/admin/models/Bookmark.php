<?php
class Model_Bookmark extends Model_UserNode
{
    protected $_name = 'user_bookmarks';

    public function getUsersBookmarks($userName = null)
    {
        if (empty($userName) || '' == $userName) {
            $identity = Digitalus_Auth::getIdentity();
            $userName = $identity->name;
        }
        $select = $this->select();
        $select->where($this->_db->quoteInto('user_name = ?', $userName));
        $select->order('label ASC');
        $result = $this->fetchAll($select);
        if ($result->count() > 0) {
            return $result;
        }
    }

    public function addUsersBookmark($label, $url, $userName = null)
    {
        if (empty($userName) || '' == $userName) {
            $identity = Digitalus_Auth::getIdentity();
            $userName = $identity->name;
        }
        $where[] = $this->_db->quoteInto('user_name = ?', $userName);
        $row = $this->fetchRow($where);
        if (!$row) {
            //the row does not exist. create it
            $data = array(
                'user_name' => $userName,
                'label'     => $label,
                'url'       => $url,
            );
            $this->insert($data);
        }
    }

    public function deleteBookmark($id)
    {
        if (empty($userName) || '' == $userName) {
            $identity = Digitalus_Auth::getIdentity();
            $userName = $identity->name;
        }
        $where[] = $this->_db->quoteInto('user_name = ?', $userName);
        $where[] = $this->_db->quoteInto('id = ?', $id);
        return $this->delete($where);
    }

}