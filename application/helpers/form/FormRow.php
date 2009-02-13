<?php
class DSF_View_Helper_Form_FormRow
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
     * @param  Zend_this->view_Interface $this->view
     * @return Zend_this->view_Helper_DeclareVars
     */
    public function setview(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }
}