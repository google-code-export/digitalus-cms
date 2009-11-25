<?php
require_once './application/modules/blog/forms/Blog.php';
require_once './application/modules/blog/models/Blog.php';

class Mod_Blog_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->breadcrumbs = array(
           $this->view->getTranslation('Modules') => $this->view->getBaseUrl() . '/admin/module',
           $this->view->getTranslation('Blog') => $this->view->getBaseUrl() . '/mod_blog'
        );
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->view->getBaseUrl() . '/admin/index/bookmark/url/mod_blog';

    }

    public function indexAction()
    {
        $blogForm = new Blog_Form();
        $blogForm->setAction($this->view->getBaseUrl() . '/mod_blog/blog/create');
        $submit = $blogForm->getElement('submit');
        $submit->setLabel($this->view->getTranslation('Create Blog'));
        $this->view->form = $blogForm;
        $mdlBlog = new Blog_Blog();
        $this->view->blogs = $mdlBlog->getBlogs();
    }

}