<?php
class Block_Folder_Form extends Digitalus_Content_Form_Abstract {
    public function setup()
    {
        $view = $this->getView();
        $content = $this->form->createElement('textarea', 'content');

        $content->setLabel($view->getTranslation('Description'))
                ->setAttrib('class', 'med');

        // Add elements to form:
        $this->form->addElement($content)
                   ->addElement('submit', 'update', array('label' => $view->getTranslation('Update Page')));
    }
}