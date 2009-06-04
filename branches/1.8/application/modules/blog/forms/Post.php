<?php
class Post_Form extends Zend_Form
{
    public function __construct($options = null) {
        parent::__construct($options);

        $id = $this->createElement('hidden', 'id');

        $blog = $this->createElement('hidden', 'blog_id');

        $title = $this->createElement('text', 'title');
        $title->setLabel('Title:');

        $teaser = $this->createElement('textarea', 'teaser');
        $teaser->setLabel('Teaser:')
               ->setAttrib('class', 'med_short');

        $content = $this->createElement('textarea', 'content');
        $content->setRequired(true)
                ->setLabel('Content')
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
?>