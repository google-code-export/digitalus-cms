<?php
class DSF_View_Helper_Content_ListWhatsNew
{
    /**
     * render a module page like news_showNewPosts
     */
    public function ListWhatsNew()
    {
        $newStories = $this->view->pageObj->getNewStories();
        if ($newStories) {
            foreach ($newStories as $story) {
                $link = DSF_Toolbox_String::addHyphens($this->view->RealPath($story->id));
                $data[] = "<a href='{$link}'>" . $this->view->pageObj->getLabel($story) . '</a>';
            }
            if (is_array($data)) {
                return $this->view->htmlList($data);
            }
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