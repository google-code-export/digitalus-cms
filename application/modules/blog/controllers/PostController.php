<?php
require_once './application/modules/blog/forms/Post.php';
require_once './application/modules/blog/models/Blog.php';
require_once './application/modules/blog/models/Post.php';

class Mod_Blog_PostController extends Digitalus_Controller_Action
{
    public function init()
    {
        parent::init();

        $this->view->breadcrumbs = array(
           $this->view->getTranslation('Modules') => $this->baseUrl . '/admin/module',
           $this->view->getTranslation('Blog')    => $this->baseUrl . '/mod_blog'
        );
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->baseUrl . '/admin/index/bookmark/url/mod_blog';

    }

    public function createAction()
    {
        $form = new Post_Form();
        $form->removeElement('content');
        $form->removeElement('teaser');
        if ($form->isValid($_POST)) {
            $values = $form->getValues();
            $mdlPost = new Blog_Post();
            $post = $mdlPost->createPost($values['blog_id'], $values['title']);
            $this->_request->setParam('id', $post->id);
            $this->_forward('edit');
        } else {
            $blogId = $_POST['blog_id'];
            if ($blogId > 0) {
                $this->_request->setParam('id', $blogId);
                $this->_forward('edit', 'blog');
            } else {
                $this->_forward('index', 'index');
            }
        }
    }

    public function editAction()
    {
        $form = new Post_Form();
        $mdlBlog = new Blog_Blog();
        $mdlPost = new Blog_Post();
        if ($this->_request->isPost() && $form->isValid($_POST)) {
            $values = $form->getValues();
            $blog = $mdlPost->updatePost(
                $values['id'],
                $values['title'],
                $values['teaser'],
                $values['content']
            );
            $post = $mdlPost->openPost($values['id']);
        } else {
            $id = $this->_request->getParam('id');
            $post = $mdlPost->openPost($id);
            $postArray['id'] = $post->id;
            $postArray['blog_id'] = $post->blogId;
            $postArray['title'] = $post->title;
            $postArray['teaser']= $post->teaser;
            $postArray['content'] = $post->content;
            $form->populate($postArray);
        }
        $blog = $mdlBlog->find($post->blogId)->current();

        $form->setAction($this->baseUrl . '/mod_blog/post/edit');
        $submit = $form->getElement('submit');
        $submit->setLabel($this->view->getTranslation('Update Post'));

        $this->view->form = $form;
        $this->view->blog = $blog;
        $this->view->post = $post;

        $this->view->breadcrumbs[$blog->name] = $this->baseUrl . '/mod_blog/blog/edit/id/' . $blog->id;
        $this->view->breadcrumbs[$post->title] = $this->baseUrl . '/mod_blog/post/edit/id/' . $post->id;
        $this->view->toolbarLinks['Delete'] = $this->baseUrl . '/mod_blog/post/delete/id/' . $post->id;

    }

    public function deleteAction()
    {
        $mdlPost = new Blog_Post();
        $id = $this->_request->getParam('id');
        $post = $mdlPost->openPost($id);
        $mdlPost->deletePageById($id);
        $this->_request->setParam('id', $post->blogId);
        $this->_forward('edit', 'blog');
    }

}