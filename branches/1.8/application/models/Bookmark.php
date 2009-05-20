<?php
class Model_Bookmark extends Model_ContentNode
{
    protected $_type = 'bookmark';

    public function getUsersBookmarks($userId = null)
    {
        $identity = Digitalus_Auth::getIdentity();
        $userId = $identity->id;

        if ($userId > 0) {
            $where[] = $this->_db->quoteInto('parent_id = ?', $userId);
            $where[] = $this->_db->quoteInto('content_type=?', $this->_type);
            $order = 'node DESC';
            $result = $this->fetchAll($where, $order);
            if ($result->count() > 0) {
                return $result;
            }
        }
    }

    public function addUsersBookmark($label, $url, $userId = null)
    {
        $identity = Digitalus_Auth::getIdentity();
        $userId = $identity->id;

        if ($userId > 0) {
            $where[] = $this->_db->quoteInto('parent_id = ?', $userId);
            $where[] = $this->_db->quoteInto('node=?', $label);
            $where[] = $this->_db->quoteInto('content_type=?', $this->_type);
            $row = $this->fetchRow($where);
            if (!$row) {
                //the row does not exist.  create it
                $data = array(
                    'content'      => $url,
                    'node'         => $label,
                    'content_type' => $this->_type,
                    'parent_id'    => $userId
                );
                $this->insert($data);
            }
        }
    }

    public function deleteBookmark($id)
    {
        $identity = Digitalus_Auth::getIdentity();
        $userId = $identity->id;

        if ($userId > 0) {
            $where[] = $this->_db->quoteInto('parent_id = ?', $userId);
            $where[] = $this->_db->quoteInto('id=?', $id);
            $where[] = $this->_db->quoteInto('content_type=?', $this->_type);
            return $this->delete($where);
        }
    }

}