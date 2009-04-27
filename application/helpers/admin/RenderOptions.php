<?php
class DSF_View_Helper_Admin_RenderOptions
{
    public $optionsPath;
    public $defaultHeadline = 'Options';

    /**
     * this helper renders the admin options.
     *
     * you can add content before the body by setting options_before placeholder
     * you can add content after the body by setting options_after placeholder
     *
     * @param unknown_type $selectedItem
     * @param unknown_type $id
     * @return unknown
     */
    public function RenderOptions($id = 'Options')
    {
        $this->setOptionsPath();

        //render the column first so you can set the headline pla
        $column = $this->renderBody();
        $headline = $this->renderHeadline();

        return $headline . $column;
    }

    public function renderHeadline()
    {
        return '<h2 class="top">' . $this->view->placeholder('optionsHeadline') . '</h2>';
    }

    public function renderBody()
    {
        $xhtml = '<div class="columnBody">';

        //you can add content before the body by setting options_before placeholder
        $xhtml .= $this->view->placeholder('options_before');

        $xhtml .= $this->view->render($this->optionsPath);

        //you can add content after the body by setting options_after placeholder
        $xhtml .= $this->view->placeholder('options_after');

        $xhtml .= '</div>';
        return $xhtml;
    }

    public function setOptionsPath()
    {
        $request = $this->view->getRequest();
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        $this->optionsPath = $controller . '/' . $action . '.options.phtml';
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