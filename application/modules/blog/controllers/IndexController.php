<?php
require_once './application/modules/blog/forms/Blog.php';
require_once './application/modules/blog/models/Blog.php';

class Mod_Blog_IndexController extends Digitalus_Controller_Action
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

    public function indexAction()
    {
        $blogForm = new Blog_Form();
        $blogForm->setAction($this->baseUrl . '/mod_blog/blog/create');
        $submit = $blogForm->getElement('submit');
        $submit->setLabel($this->view->getTranslation('Create Blog'));
        $this->view->form = $blogForm;
        $mdlBlog = new Blog_Blog();
        $this->view->blogs = $mdlBlog->getBlogs();
    }

}