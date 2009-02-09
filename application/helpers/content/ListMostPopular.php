<?php
class DSF_View_Helper_Content_ListMostPopular
{
    /**
     * render a module page like news_showNewPosts
     */
    public function ListMostPopular()
    {
        $popular = $this->view->pageObj->getPopularStories();
        if ($popular) {
            foreach ($popular as $story) {
                $link = DSF_Toolbox_String::addHyphens($this->view->RealPath($story->id));
                $data[] = "<a href='{$link}'>" . $this->view->pageObj->getLabel($story) . "</a>";
            }
            if (is_array($data)) {
                return $this->view->htmlList($data);
            }
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
}