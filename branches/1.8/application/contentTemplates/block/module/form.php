<?php
class Block_Module_Form extends DSF_Content_Form_Abstract
{
    public function setup()
    {
        $view = $this->getView();

        $contentAbove = $this->form->createElement('textarea', 'content_above');
        $contentAbove->setRequired(false)
                     ->setLabel($view->getTranslation('Content Above') . ':')
                     ->setAttrib('cols', 40)
                     ->setAttrib('rows', 8);

        $partial = $this->form->createElement('partial', 'module', array('Partial' => 'module/partials/load-module.phtml'));
        $partial->setLabel($view->getTranslation('Select a module page'));

        $contentBelow = $this->form->createElement('textarea', 'content_below');
        $contentBelow->setRequired(false)
                     ->setLabel($view->getTranslation('Content below') . ':')
                     ->setAttrib('cols', 40)
                     ->setAttrib('rows', 8);

        // Add elements to form:
        $this->form->addElement($contentAbove)
                   ->addElement($partial)
                   ->addElement($contentBelow)
                   ->addElement('submit', 'update', array('label' => $view->getTranslation('Update Page')));
    }
}