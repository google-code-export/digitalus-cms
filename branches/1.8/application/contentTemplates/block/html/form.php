<?php
class Block_Html_Form extends DSF_Content_Form_Abstract {
    public function setup()
    {
        $view = $this->getView();
        $content = $this->form->createElement('textarea', 'block');

        $content->setRequired(true)
                ->setLabel($view->getTranslation('Content'))
                ->setDecorators(array('Composite'))
                ->setAttrib('class', 'editor html');

        // Add elements to form:
        $this->form->addElement($content)
                   ->addElement('submit', 'update', array('label' => $view->getTranslation('Update Page')));
    }
}