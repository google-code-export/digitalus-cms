<?php
class Digitalus_View_Helper_ContentControl extends Zend_View_Helper_Abstract
{
    public $id;
    public $attribs = array();
    public $content = null;
    protected $_control = null;

    public function contentControl($type, $id, $content = null, $attribs = array())
    {
        $this->id = $id;
        $this->content[$id] = $content;
        $form = $this->getForm();
        if ($form != NULL) {
            // if a form is registered then you want to add the control to the form and return it
            return $this->getControl($type, $id, $attribs);
        } else {
            // otherwise render the content
            return $this->render();
        }
    }

    public function render()
    {
        return '<div class="digitalusContentControl">' . $this->content[$this->id] . '</div>';
    }

    public function getControl($type, $id, $attribs)
    {
        if (isset($attribs['required'])) {
            $required = true;
            unset($attribs['required']);
        } else {
            $required = false;
        }

        if (isset($attribs['label'])) {
            $label = $attribs['label'];
            unset($attribs['label']);
        } else {
            $label = $id;
        }

        $form = $this->getForm();
        $control = $form->createElement($type, $id, $attribs);
        $control->setLabel($label);
        $control->setRequired($required);
        $control->setValue($this->content[$id]);
        $form->addElement($control);

        return $control;
    }

    public function getForm()
    {
        if (isset($this->view->formInstance)) {
            return $this->view->formInstance;
        } else {
            return NULL;
        }
    }
}
?>