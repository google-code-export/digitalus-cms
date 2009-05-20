<?php
class Base_Wysiwyg_Form extends Digitalus_Content_Form_Abstract
{
    public function setup ()
    {
        $headline = $this->form->createElement('text', 'headline');
        $headline->setLabel('Headline:');
        $teaser = $this->form->createElement('textarea', 'teaser');
        $teaser->setLabel('Teaser:')
               ->setAttrib('class', 'med_short');
        $content = $this->form->createElement('textarea', 'content');
        $content->setRequired(true)->setLabel('Content')
                                   ->setDecorators(array('Composite'))->setAttrib('class', 'editor fckeditor');
        // Add elements to form:
        $this->form->addElement($headline)
                   ->addElement($teaser)
                   ->addElement($content)
                   ->addElement('submit', 'update', array('label' => 'Update Page'));
    }
}