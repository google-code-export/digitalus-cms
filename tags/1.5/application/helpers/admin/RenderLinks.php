<?php
class DSF_View_Helper_Admin_RenderLinks
{

    /**
     * comments
     */
    public function RenderLinks($links, $class, $prependText = null, $appendText = null, $separator = ' | ')
    {
        if (is_array($links) && count($links) > 0) {
            foreach ($links as $label => $link) {
                $linkClass = strtolower($label);
                $linkClass = str_replace(' ', '_', $linkClass);
                $hyperlinks[] = '<a href="' . $link . '" class="' . $linkClass . '">' . $this->view->getTranslation($label) . '</a>';
            }
            return '<p class="' . $class . '">' . $prependText . implode($separator, $hyperlinks) . $appendText . '</p>';
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