<?php
class Block_Folder_Form extends DSF_Content_Form_Abstract {
    public function setup()
    {
        $view = $this->getView();
        $content = $this->form->createElement('textarea', 'content');

        $content->setLabel($view->GetTranslation('Description'))
                ->setAttrib('class', 'med');

        // Add elements to form:
        $this->form->addElement($content)
                   ->addElement('submit', 'update', array('label' => $view->GetTranslation('Update Page')));
    }
}