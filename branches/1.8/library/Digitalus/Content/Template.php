<?php
class Digitalus_Content_Template
{
    public $template;
    public $templatePath = '/public/views/scripts/layouts/sublayouts';
    public $properties;

    public function __construct($template, $templatePath = null)
    {
        
        $this->template = $template;
        if($templatePath != NULL) {
            $this->templatePath = $templatePath;
        }
    }

    public function getAllowedChildTemplates()
    {
        /* deprecated as of version 1.8
        if (isset($this->_properties->allowedChildren)) {
            if (isset($this->_properties->allowedChildren)) {
                //templates are set
                //turn them into an array and return them
                if (is_object($this->_properties->allowedChildren)) {
                    $allowedChildren = $this->_properties->allowedChildren->toArray();
                    //this is a hack around how zf works its config->toArray function
                    if (!is_array($allowedChildren['template'])) {
                        $allowedChildren['template'] = array($allowedChildren['template']);
                    }
                    foreach ($allowedChildren['template'] as $template) {
                        $templateName = (string)$template;
                        $allowedTemplates[$template] = ucwords(str_replace('_',' ', $templateName));
                    }
                    return $allowedTemplates;
                }
            }
            // there are no allowed templates
            return false;
        } else {
            //if no template is passed then you can add any subtemplate
            $loader = new Digitalus_Content_Template_Loader();
            return $loader->getTemplates();
        }
		*/
    }

    public function getForm()
    {
        $form = new Digitalus_Content_Form();
        $view = clone($form->getView());
        $view->setScriptPath(APPLICATION_PATH . $this->templatePath);
        $view->formInstance = $form;
        $page = $view->render($this->template . '.phtml');
        return $form;
    }

    public function render($content)
    {
        $form = new Digitalus_Content_Form();
        $view = clone($form->getView());
        $view->setScriptPath(APPLICATION_PATH . $this->templatePath);
        $view->content = $content;
        return $view->render($this->template . '.phtml');
    }

}