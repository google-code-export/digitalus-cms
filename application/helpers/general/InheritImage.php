<?php
/**
 * this helper renders the current file's image attachment
 * if it does not have one then it goes up the line
 *
 */
class DSF_View_Helper_General_InheritImage
{
    public function InheritImage()
    {
        if (empty($this->view->page->filepath)){
            $parents = $this->view->pageObj->getParents('ASC');
            if (is_array($parents)) {
                foreach ($parents as $parent){
                    if (!empty($parent->filepath)) {
                        return $this->renderImage($parent->filepath);
                    }
                }
            }
        } else {
            return $this->renderImage($this->view->page->filepath);
        }
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

    public function renderImage($filepath){
        return '<img src="/' . $filepath '" class="reflect" />';
    }
}