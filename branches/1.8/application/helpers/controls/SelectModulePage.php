<?php
class DSF_View_Helper_Controls_SelectModulePage
{
    public function SelectModulePage($name, $module, $value, $attribs = null)
    {
        $pages = DSF_Filesystem_File::getFilesByType('./application/modules/' . $module . '/views/scripts/public', 'phtml');
        if (is_array($pages)) {
            $data[] = $this->view->getTranslation('Select One');
            foreach ($pages as $page) {
                $page = DSF_Toolbox_Regex::stripFileExtension($page);
                $data[$page] = $page;
            }
            return $this->view->formSelect($name, $value, $attribs, $data);
        } else {
            return $this->view->getTranslation('There are no pages in this module');
        }
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