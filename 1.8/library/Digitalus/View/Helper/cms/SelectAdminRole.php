<?php
class Zend_View_Helper_SelectAdminRole
{
    public function SelectAdminRole($name, $value, $attribs = false)
    {
        $data['admin']      = $this->view->getTranslation('Site Administrator');
        $data['superadmin'] = $this->view->getTranslation('Super Administrator');
        return $this->view->formSelect($name, $value, $attribs, $data);
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