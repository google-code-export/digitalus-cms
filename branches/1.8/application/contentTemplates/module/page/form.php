<?php
class Module_Page_Form extends Digitalus_Content_Form_Abstract
{
    public function setup()
    {
        $view = $this->getView();
        $headline = $this->form->createElement('text', 'headline');
        $headline->setLabel($view->getTranslation('Headline') . ':');

        $tagline = $this->form->createElement('textarea', 'tagline');
        $tagline->setLabel($view->getTranslation('Tagline') . ':')
                ->setAttrib('class', 'med_short');

        $contentAbove = $this->form->createElement('textarea', 'content_above');
        $contentAbove->setRequired(false)
                     ->setLabel($view->getTranslation('Content Above') . ':')
                     ->setAttrib('class', 'med_short');

        $partial = $this->form->createElement('partial', 'module', array('Partial' => 'module/partials/load-module.phtml'));
        $partial->setLabel($view->getTranslation('Select a module page'));

        $contentBelow = $this->form->createElement('textarea', 'content_below');
        $contentBelow->setRequired(false)
                     ->setLabel($view->getTranslation('Content below') . ':')
                     ->setAttrib('class', 'med_short');

        // Add elements to form:
        $this->form->addElement($headline)
                   ->addElement($tagline)
                   ->addElement($contentAbove)
                   ->addElement($partial)
                   ->addElement($contentBelow)
                   ->addElement('submit', 'update', array('label' => $view->getTranslation('Update Page')));
    }
}