<?php
class DSF_Builder_Action_Design extends DSF_Builder_Abstract
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
        $view->content = $content;

        //render the content template
        $templateParts = explode('_',$template);
        $view->placeholder('content')->set($view->render($templateParts[0] . '/' . $templateParts[1] . '/template.phtml'));
    }


    public function loadDesign()
    {
        $data = $this->_page->getData();
        $designId = $data->design;

        //load the parents or default if the current page does not have a design set
        if (!empty($designId)) {
            $page = new Page();
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
            $mdlDesign = new Design();
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
        if($inlineStyles) {
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
        Zend_Debug::dump($view->test);
        $config = Zend_Registry::get('config');
        $pathToLayouts = $config->design->pathToPublicLayouts;
        if(is_dir($pathToLayouts)) {
            $view->addScriptPath($pathToLayouts);
            $design = $this->_page->getDesign();
            $layout = $design->getLayout();
            if(file_exists($pathToLayouts . '/' . $layout)) {
                $this->_page->setLayout($layout);
            }else{
                throw new Zend_Exception("The layout file specified in your design does not exist");
            }
        } else {
            throw new Zend_Exception("The layout folder specified in your site config file does not exist");
        }
    }

}