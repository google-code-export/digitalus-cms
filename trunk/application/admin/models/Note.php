<?php
class Model_Note extends Model_UserNode
{
    protected $_name = 'user_notes';

    public function getUsersNotes($userName = null)
    {
        if (empty($userName) || '' == $userName) {
            $identity = Digitalus_Auth::getIdentity();
            $userName = $identity->name;
        }
        $where[] = $this->_db->quoteInto('user_name = ?', $userName);
        $row = $this->fetchRow($where);
        if ($row) {
            return $row;
        } else {
            //the row does not exist. create it
            $data = array(
                'user_name' => $userName,
                'content'   => $this->view->getTranslation('You have no notes to view'),
            );
            $this->insert($data);
            return $this->find($this->_db->lastInsertId())->current();
        }
    }

    public function saveUsersNotes($notes, $userName = null)
    {
        if (empty($userName) || '' == $userName) {
            $identity = Digitalus_Auth::getIdentity();
            $userName = $identity->name;
        }
        $where[] = $this->_db->quoteInto('user_name = ?', $userName);
        $row = $this->fetchRow($where);
        if ($row) {
            $row->content = $notes;
            $row->save();
        } else {
            //the row does not exist.  create it
            $data = array(
                'user_name' => $userName,
                'content'   => $notes,
            );
            $this->insert($data);
        }
    }
}