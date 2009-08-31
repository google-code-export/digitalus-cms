<?php
class Digitalus_Builder_Action_Design extends Digitalus_Builder_Abstract
{
    public function setTemplate()
    {
        //get the view instance
        $view = $this->_page->getView();
        $config = Zend_Registry::get('config');
        $view->addScriptPath($config->filepath->contentTemplates);

        //get the page object and template
        $templateParts = explode('_', $this->_page->getContentTemplate());
        $template = isset($templateParts[0])? $templateParts[0] :  $config->template->public->template;
        $this->_page->setParam('template_template', $template);
        $page = isset($templateParts[1])? $templateParts[1] :  $config->template->public->page;
        $this->_page->setParam('template_page', $page);

        // load the template
        $pathToTemplate = BASE_PATH . '/' . $config->template->pathToTemplates . '/public/' . $template;
        $view->addScriptPath($pathToTemplate . '/layouts');
        $digitalusTemplate = new Digitalus_Interface_Template();
        $this->_page->setParam('template_data', $digitalusTemplate->getPageData($template, $page));
    }

    public function setStyles()
    {
        $view = $this->_page->getView();
        $templatePath = $view->getBaseUrl() . '/' . Zend_Registry::get('config')->template->pathToTemplates . '/public/' . $this->_page->getParam('template_template');
        $templateData = $this->_page->getParam('template_data');
        if($templateData->styles) {
            foreach ($templateData->styles->stylesheet as $style) {
                $attr = $style->attributes();
                if(is_object($attr) && (string)$attr['import'] == 'true') {
                    if(substr($style, 0, 4) != 'http') {
                        $style = $view->getBaseUrl() . '/' . $style;
                    }
                    $view->headLink()->appendStylesheet($style);
                }else{
                   $view->headLink()->appendStylesheet($templatePath . '/styles/' . (string)$style);
                }
            }
        }
    }

    public function setFilters()
    {
        //get the view instance
        $view = $this->_page->getView();
        $view->setFilterPath('Digitalus/View/Filter');
        $view->addFilter('digitalusControl');
        $view->addFilter('digitalusModule');
        $view->addFilter('digitalusPartial');
        $view->addFilter('digitalusNavigation');
    }

    public function renderTemplate()
    {
        $view = $this->_page->getView();
        $view->content = $this->_page->getContent();
        $view->page = $this->_page;
        $xhtml = $view->render($this->_page->getParam('template_data')->layout);
        $this->_page->setParam('xhtml', $xhtml);
    }
}