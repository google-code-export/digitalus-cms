<?php
require_once './application/modules/blog/models/Blog.php';
require_once './application/modules/blog/models/Post.php';

class Mod_Blog_PublicController extends Zend_Controller_Action
{
    public $moduleData;

    public function init()
    {
        $module = new Digitalus_Module();
        $this->moduleData = $module->getData();
    }

    public function blogAction()
    {
        if ($this->moduleData->blog > 0) {
            $mdlBlog = new Blog_Blog();
            $mdlPost = new Blog_Post();

            $page = Digitalus_Builder::getPage();
            $params = $page->getParams();
            if (isset($params['openPost']) && $params['openPost'] > 0) {
                $openPost = $mdlPost->openPost($params['openPost']);
                $this->view->openPost = $openPost;
                $this->view->blog = $mdlBlog->find($openPost->blogId)->current();
            } else {
                $this->view->blog = $mdlBlog->find($this->moduleData->blog)->current();
                $this->view->posts = $mdlPost->getPosts($this->moduleData->blog);
            }
        }
    }
}