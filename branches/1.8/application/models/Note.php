<?php
class Model_Note extends Model_ContentNode
{
    protected $_type = 'note';
    protected $_namespace = 'user';

    public function getUsersNotes($userId = null)
    {
        $identity = DSF_Auth::getIdentity();
        $userId = $identity->id;

        if ($userId > 0) {
            $where[] = $this->_db->quoteInto('parent_id = ?', $this->_namespace . '_' . $userId);
            $where[] = $this->_db->quoteInto('node = ?', $this->_type);
            $row = $this->fetchRow($where);
            if ($row) {
                return $row;
            } else {
                //the row does not exist.  create it
                $data = array(
                    'content'   => 'You have no notes to view',
                    'node'      => $this->_type,
                    'parent_id' => $this->_namespace . '_' . $userId
                );
                $this->insert($data);
                return $this->find($this->_db->lastInsertId())->current();
            }
        }
    }

    public function saveUsersNotes($notes, $userId = null)
    {
        $identity = DSF_Auth::getIdentity();
        $userId = $identity->id;

        if ($userId > 0) {
            $where[] = $this->_db->quoteInto('parent_id = ?', $this->_namespace . '_' . $userId);
            $where[] = $this->_db->quoteInto('node = ?', $this->_type);
            $row = $this->fetchRow($where);
            if ($row) {
                $row->content = $notes;
                $row->save();
            } else {
                //the row does not exist.  create it
                $data = array(
                    'content'   => $notes,
                    'node'      => $this->_type,
                    'parent_id' => $this->_namespace . '_' . $userId
                );
                $this->insert($data);
            }
        }
    }
}