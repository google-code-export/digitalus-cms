<?php
class Blog_Post extends Model_Page
{
    protected $_namespace = 'blog_post';

    public function getPosts($blogId)
    {
        $blogId = intval($blogId);
        $posts = $this->getChildren($blogId, null, 'create_date');
        if ($posts != null) {
            foreach ($posts as $post) {
                $postArray[] = $this->openPost($post->id);
            }
            return $postArray;
        } else {
            return null;
        }

    }

    public function openPost($postId)
    {
        $post = $this->find($postId)->current();
        if ($post) {
            $mdlContentNode = new Model_ContentNode();
            $content = $mdlContentNode->fetchContentArray($post->id, null, null, $this->getDefaultVersion());
            $objPost = new stdClass();
            $objPost->id = $post->id;
            $objPost->title = $post->name;
            $objPost->dateCreated = $post->create_date;
            $objPost->blogId = $post->parent_id;

            if (isset($content['teaser'])) {
                $objPost->teaser = $content['teaser'];
            } else {
                $objPost->teaser = null;
            }
            if (isset($content['content'])) {
                $objPost->content = $content['content'];
            } else {
                $objPost->content = null;
            }
            $mdlUser = new Model_User();
            $author = $mdlUser->find($post->author_id)->current();
            if ($author) {
                $objPost->author = $author->first_name . ' ' . $author->last_name;
            } else {
                $objPost->author = null;
            }
            $objPost->author_id = $post->author_id;
            return $objPost;
        } else {
            return null;
        }
    }

    public function createPost($blogId, $title)
    {
        return $this->createPage($title, $blogId);
    }

    public function updatePost($postId, $title, $teaser, $content)
    {
        $data = array(
            'page_id' => $postId,
            'name'    => $title,
            'teaser'  => $teaser,
            'content' => $content
        );
        $this->edit($data);
    }
}
?>