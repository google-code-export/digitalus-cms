<?php
class Digitalus_Builder_Action_Design extends Digitalus_Builder_Abstract
{
    public function loadContentTemplate()
    {
        //get the view instance
        $view = $this->_page->getView();
        $config = Zend_Registry::get('config');
        $view->addScriptPath($config->filepath->contentTemplates);

        //get the page object and template
        $template = $this->_page->getContentTemplate();
        $content = $this->_page->getContent();
        $pageTemplate = new Digitalus_Content_Template($template);
        $page = $pageTemplate->render($content);
        $view->placeholder('content')->set($page);
    }

    public function loadDesign()
    {
        

        // if the design id is passed as a url parameter use that design instead
        $uri = new Digitalus_Uri();
        $params = $uri->getParams();
        if(is_array($params) && isset($params['preview_design'])) {
            $design = $params['preview_design'];
        }else{
            $data = $this->_page->getData();
            $design = $data->design;
        }
        //load the parents or default if the current page does not have a design set
        if (empty($design)) {
            $page = new Model_Page();
            $parents = $page->getParents($this->_page->getId());
            if (is_array($parents)) {
                foreach ($parents as $parent) {
                    if (!empty($parent->design)) {
                        $design = $parent->design;
                        break;
                    }
                }
            }
        }

        if (empty($design)) {
            $mdlDesign = new Model_Design();
            $default = $mdlDesign->getDefaultDesign();
            $design = $default->id;
        }
        $this->_page->setDesign($design);
    }

    public function setStyles()
    {
        $view = $this->_page->getView();
        $design = $this->_page->getDesign();
        //the design model returns the stylesheets organized by skin
        $skins = $design->getStylesheets();
        if (is_array($skins)) {
            foreach ($skins as $skin => $styles) {
                if (is_array($styles)) {
                    foreach ($styles as $style) {
                        $view->headLink()->appendStylesheet($this->_page->getBaseUrl() . '/skins/' . $skin . '/styles/' . $style);
                    }
                }
            }
        }
        $inlineStyles = $design->getInlineStyles();
        if ($inlineStyles) {
            $view->headStyle()->setStyle($inlineStyles);
        }
    }

    public function setScripts()
    {
        $design = $this->_page->getDesign();
        $scripts = $design->getScripts();
        if (is_array($scripts)) {
            $view = $this->_page->getView();
            foreach ($scripts as $script) {
                $view->headScript()->appendFile($this->_page->getBaseUrl() . '/' . $script);
            }
        }
    }

    public function setLayout()
    {
        $view = $this->_page->getView();
        $config = Zend_Registry::get('config');
        $pathToLayouts = $config->design->pathToPublicLayouts;
        if (is_dir($pathToLayouts)) {
            $view->addScriptPath($pathToLayouts);
            $design = $this->_page->getDesign();
            $layout = $design->getLayout();
            if (file_exists($pathToLayouts . '/' . $layout)) {
                $this->_page->setLayout($layout);
            } else {
                throw new Zend_Exception('The layout file specified in your design does not exist');
            }
        } else {
            throw new Zend_Exception('The layout folder specified in your site config file does not exist');
        }
    }

}