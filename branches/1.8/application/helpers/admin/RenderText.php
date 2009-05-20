<?php
class Digitalus_View_Helper_Admin_RenderText
{

    /**
     *
     * @return unknown
     */
    public function RenderText($key, $tag = null)
    {
        $xhtml = null;
        if ($tag != null) {
            $xhtml .= "<{$tag}>";
        }
        $xhtml .= $this->view->getTranslation($key);
        if ($tag != null) {
            $xhtml .= "</{$tag}>";
        }
        return $xhtml;

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
