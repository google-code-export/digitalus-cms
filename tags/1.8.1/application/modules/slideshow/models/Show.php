<?php
class Slideshow_Show extends Model_Page
{
    protected $_namespace = "slideshow_show";

    public function createShow($name)
    {
        return $this->createPage($name);
    }

    public function updateShow($id, $name, $description)
    {
        $data = array(
            'page_id'       => $id,
            'name'          => $name,
            'description'   => $description
        );
        return $this->edit($data);
    }

    public function deleteShow($id)
    {
         $this->deletePageById($id);
    }

    public function getShows()
    {
        $select = $this->select();
        $select->where('namespace = ?', $this->_namespace);
        $select->order('name');
        $result = $this->fetchAll($select);
        if($result->count() > 0) {
            return $result;
        }else{
            return null;
        }
    }
}
?>