<?php
class DSF_View_Helper_Content_RenderContent
{
    public function RenderContent($block, $rowset = null, $wordCount = 0)
    {
        if ($rowset == null) {
           $content = $this->view->page->getContent();
        } else {
            $content = $rowset;
        }

        $xhtml = '';

        if ($wordCount > 0){
          $xhtml .= $this->view->TruncateText($content->$block, $wordCount);
        } else {
          $xhtml .= $content->$block;
        }

        return stripslashes($xhtml);
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