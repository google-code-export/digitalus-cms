<?php
class Base_Section_Form extends Digitalus_Content_Form_Abstract {
    public function setup() {
        $view = $this->getView();
        $headline = $this->form->createElement('text', 'headline');
        $headline->setLabel($view->getTranslation('Headline') . ':');

        $tagline = $this->form->createElement('textarea', 'tagline');
        $tagline->setLabel($view->getTranslation('Tagline') . ':')
                ->setAttrib('class', 'med_short');

        $content = $this->form->createElement( 'textarea', 'content' );

        $content->setRequired(true)
                ->setLabel($view->getTranslation('Content') . ':')
                ->setDecorators(array('Composite'))
                ->setAttrib('class', 'editor wiki');

        // Add elements to form:
        $this->form->addElement($headline)
                   ->addElement($tagline)
                   ->addElement($content)
                   ->addElement('submit', 'update', array('label' => $view->getTranslation('Update Page')));
    }
}