<?php
class Digitalus_View_Helper_Content_ListMostPopular
{
    /**
     * render a module page like news_showNewPosts
     */
    public function ListMostPopular()
    {
        $popular = $this->view->pageObj->getPopularStories();
        if ($popular) {
            foreach ($popular as $story) {
                $link = Digitalus_Toolbox_String::addHyphens($this->view->RealPath($story->id));
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
     * @param  Zend_View_Interface $view
     * @return Zend_View_Helper_DeclareVars
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }

}