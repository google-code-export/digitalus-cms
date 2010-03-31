<?php
/**
 *
 * @author Forrest
 * @version
 */
require_once 'Zend/View/Interface.php';
require_once './application/modules/blog/models/Blog.php';
/**
 * SelectBlog helper
 *
 * @uses viewHelper Zend_View_Helper
 * @uses Digitalus_Form
 * @uses model Blog_Blog
 */
class Zend_View_Helper_SelectBlog
{
    /**
     * @var Zend_View_Interface
     */
    public $view;
    /**
     *
     */
    public function selectBlog ($name, $value)
    {
        $mdlBlog = new Blog_Blog();
        $blogs = $mdlBlog->getBlogs();
        if ($blogs == null) {
            return $this->view->getTranslation('There are no blogs to view!');
        } else {
            $options[] = $this->view->getTranslation('Select One');
            foreach ($blogs as $blog) {
                $options[$blog->id] = $blog->name;
            }
            $form = new Digitalus_Form();
            $select = $form->createElement('select', $name, array(
                'multiOptions' => $options,
                'belongsTo'    => 'module',
            ));
            return $select;
        }
    }
    /**
     * Sets the view field
     * @param $view Zend_View_Interface
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }
}
