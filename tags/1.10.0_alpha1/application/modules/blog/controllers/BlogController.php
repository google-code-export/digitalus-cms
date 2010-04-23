<?php
require_once './application/modules/blog/forms/Blog.php';
require_once './application/modules/blog/forms/Post.php';
require_once './application/modules/blog/models/Blog.php';
require_once './application/modules/blog/models/Post.php';

class Mod_Blog_BlogController extends Digitalus_Controller_Action
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
        $form = new Blog_Form();
        if ($form->isValid($_POST)) {
            $values = $form->getValues();
            $mdlBlog = new Blog_Blog();
            $blog = $mdlBlog->createBlog($values['name']);
            $this->_request->setParam('id', $blog->id);
            $this->_request->setParam('isInsert', true);
            $this->_forward('edit');
        } else {
            $this->_forward('index', 'index');
        }
    }

    public function editAction()
    {
        $form = new Blog_Form();
        $mdlBlog = new Blog_Blog();
        if ($this->_request->isPost() && $form->isValid($_POST) && $this->_request->getParam('isInsert') != true) {
            $values = $form->getValues();
            $blog = $mdlBlog->updateBlog($values['id'], $values['name']);
            $blog = $blog->page; //the update blog function returns the results of open
        } else {
            $id = $this->_request->getParam('id');
            $blog = $mdlBlog->find($id)->current();
            $form->populate($blog->toArray());
        }
        $form->setAction($this->baseUrl . '/mod_blog/blog/edit');
        $submit = $form->getElement('submit');
        $submit->setLabel($this->view->getTranslation('Update Blog'));

        $this->view->form = $form;
        $this->view->blog = $blog;
        $mdlPost = new Blog_Post();
        $this->view->posts = $mdlPost->getPosts($blog->id);

        $postForm = new Post_Form();
        $postFormValues['blog_id'] = $blog->id;
        $postForm->populate($postFormValues);
        $postForm->setAction($this->baseUrl . '/mod_blog/post/create');
        $submit = $postForm->getElement('submit');
        $submit->setLabel($this->view->getTranslation('Add New Post'));
        $this->view->postForm = $postForm;
        $this->view->breadcrumbs[$blog->name] = $this->baseUrl . '/mod_blog/blog/edit/id/' . $blog->id;
        $this->view->toolbarLinks['Delete'] = $this->baseUrl . '/mod_blog/blog/delete/id/' . $blog->id;

    }

    public function deleteAction()
    {
        $id = $this->_request->getParam('id');
        $mdlBlog = new Blog_Blog();
        $mdlBlog->deletePageById($id);
        $this->_forward('index', 'index');
    }

}