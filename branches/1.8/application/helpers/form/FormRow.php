<?php
class Digitalus_View_Helper_Form_FormRow
{

    /**
     * comments
     */
    public function FormRow($label, $control, $required = false)
    {
        $class = null;
        if ($required) {
            $class = ' required';
        }
        $xhtml[] = '<dt><label class="formRow' . $class . '">' . $label . '</label></dt>' . PHP_EOL;
        $xhtml[] = '<dd>' . $control . '</dd>';
        return implode(null, $xhtml);
    }

    /**
     * Set this->view object
     *
     * @param  Zend_View_Interface $view
     * @return Zend_View_Helper_DeclareVars
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }

}