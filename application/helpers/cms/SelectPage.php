<?php
class Zend_View_Helper_SelectPage
{
    public function SelectPage($name, $value = null, $attribs = null)
    {
        $mdlIndex = new Page();
        $index = $mdlIndex->getIndex(0, 'name');

        $pages = array();
        $pages[0] = $this->view->GetTranslation('Site Root');

        if (is_array($index)) {
            foreach ($index as $id => $page) {
                $pages[$id] = $page;
            }
        }

        return $this->view->formSelect($name, $value, $attribs, $pages);
    }

    /**
     * Set this->view object
     *
     * @param  Zend_this->view_Interface $this->view
     * @return Zend_this->view_Helper_DeclareVars
     */
    public function setview(Zend_view_Interface $view)
    {
        $this->view = $view;
        return $this;
    }
}