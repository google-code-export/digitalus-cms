<?php
class Block_Wiki_Form extends DSF_Content_Form_Abstract {
    public function setup() {
        $view = $this->getView();

        $content = $this->form->createElement('textarea', 'block');

        $content->setRequired(true)
                ->setLabel($view->GetTranslation('Content'))
                ->setDecorators(array('Composite'))
                ->setAttrib('class', 'editor wiki');

        // Add elements to form:
        $this->form->addElement($content)
                   ->addElement('submit', 'update', array('label' => $view->GetTranslation('Update Page')));
    }
}