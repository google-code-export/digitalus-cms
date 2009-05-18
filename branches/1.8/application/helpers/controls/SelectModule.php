<?php
class DSF_View_Helper_Controls_SelectModule
{
    public function SelectModule($name, $value, $attribs = null)
    {
        $modules = DSF_Filesystem_Dir::getDirectories('./application/modules');
        if (is_array($modules)) {
            $data[] = $this->view->getTranslation('Select a module');
            foreach ($modules as $module) {
                $pages = DSF_Filesystem_File::getFilesByType('./application/modules/' . $module . '/views/scripts/public', 'phtml');
                if (is_array($pages)) {
                    foreach ($pages as $page) {
                        $page = DSF_Toolbox_Regex::stripFileExtension($page);
                        $data[$module . '_' . $page] = $module . ' -> ' . $page;
                    }
                }
            }
            return $this->view->formSelect($name, $value, $attribs, $data);
        } else {
            return $this->view->getTranslation('There are no modules currently installed');
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