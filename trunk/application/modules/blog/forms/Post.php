<?php
class Post_Form extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);

        $view = $this->getView();

        $id = $this->createElement('hidden', 'id');

        $blog = $this->createElement('hidden', 'blog_id');

        $title = $this->createElement('text', 'title');
        $title->setLabel($view->getTranslation('Title') . ':');

        $teaser = $this->createElement('textarea', 'teaser');
        $teaser->setLabel($view->getTranslation('Teaser' . ':'))
               ->setAttrib('class', 'med_short');

        $content = $this->createElement('textarea', 'content');
        $content->setRequired(true)
                ->setLabel($view->getTranslation('Content'))
                ->setAttrib('class', 'editor wysiwyg');

        $submit = $this->createElement('submit', 'submit');

        $this->setMethod('post')
             ->addElement($id)
             ->addElement($blog)
             ->addElement($title)
             ->addElement($teaser)
             ->addElement($content)
             ->addElement($submit);
    }
}