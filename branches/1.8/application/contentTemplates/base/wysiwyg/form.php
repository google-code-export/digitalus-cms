<?php
class Base_Wysiwyg_Form extends DSF_Content_Form_Abstract
{
    public function setup ()
    {
        $view = $this->getView();
        $headline = $this->form->createElement('text', 'headline');
        $headline->setLabel($view->getTranslation('Headline') . ':');
        $teaser = $this->form->createElement('textarea', 'teaser');
        $teaser->setLabel($view->getTranslation('Teaser') . ':')
               ->setAttrib('class', 'med_short');
        $content = $this->form->createElement('textarea', 'content');
        $content->setRequired(true)->setLabel($view->getTranslation('Content'))
                                   ->setDecorators(array('Composite'))->setAttrib('class', 'editor wysiwyg');
        // Add elements to form:
        $this->form->addElement($headline)
                   ->addElement($teaser)
                   ->addElement($content)
                   ->addElement('submit', 'update', array('label' => $view->getTranslation('Update Page')));
    }
}