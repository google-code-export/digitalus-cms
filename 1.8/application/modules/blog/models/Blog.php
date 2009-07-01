<?php
class Blog_Blog extends Model_Page
{
    protected $_namespace = 'blog';

    public function createBlog($name)
    {
        return $this->createPage($name);
    }

    public function updateBlog($id, $name)
    {
        $data = array(
            'page_id' => $id,
            'name'    => $name
        );
        return $this->edit($data);
    }

    public function deleteBlog($id)
    {
        $this->deletePageById($id);
    }

    public function getBlogs()
    {
        $select = $this->select();
        $select->where('namespace = ?', $this->_namespace);
        $select->order('name');
        $result = $this->fetchAll($select);
        if ($result->count() > 0) {
            return $result;
        } else {
            return null;
        }
    }
}
?>