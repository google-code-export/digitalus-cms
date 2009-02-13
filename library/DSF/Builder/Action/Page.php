<?php
class DSF_Builder_Action_Page extends DSF_Builder_Abstract
{
    public function setUri()
    {
        $uri = new DSF_Uri();
        $this->_page->setUri($uri->toArray());
    }

    public function appendUriParams()
    {
        $uri = new DSF_Uri();
        $params = $uri->getParams();
        if (is_array($params)) {
            $this->_page->setParams($params);
        }
    }

    public function setBaseUrl()
    {
        $front = Zend_Controller_Front::getInstance();
        $baseUrl = $front->getBaseUrl();
        $this->_page->setBaseUrl($baseUrl);
    }

    public function setPointer()
    {
        $mdlPage = new Page();
        $uri = $this->_page->getUri();
        $pointer = $mdlPage->fetchPointer($uri);
        if($pointer > 0) {
            $this->_page->setId($pointer);
        }else{
            //this needs to be refactored so the error controller can handle it
            $front = Zend_Controller_Front::getInstance();
            $response = $front->getResponse();
            $response->setRawHeader('HTTP/1.1 404 Not Found');
            throw new Zend_Exception("The page you requested was not found.  Digitalus CMS could not locate the error page either.");
        }
    }

    public function setParents()
    {
        $mdlPage = new Page();
        $parents = $mdlPage->getParents($this->_page->getId());
        if (is_array($parents)) {
            $this->_page->setParents($parents);
        }
    }

    public function loadData()
    {
        $mdlPage = new Page();
        $row = $mdlPage->find($this->_page->getId())->current();
        if ($row) {
            $this->_page->setData($row);
        }
    }

    public function setLanguage()
    {
        $lang = $this->_page->getParam('lang');
        if (!empty($lang)) {
            DSF_Language::setLanguage($lang);
            $this->_page->setLanguage($lang);
        } else {
            $this->_page->setLanguage(DSF_Language::getLanguage());
        }

    }

    public function setAvailableLanguages()
    {
        $page = new Page();
        $availableContent = $page->getVersions($this->_page->getId());
        $this->_page->setAvailableLanguages($availableContent);
    }

    public function loadContent()
    {
        $mdlPage = new Page();
        $content = $mdlPage->open($this->_page->getId(), $this->_page->getLanguage());
        $this->_page->setContent($content->content);
        $this->_page->setDefaultContent($content->defaultContent);
    }

    public function loadContentTemplate()
    {
        $pageData = $this->_page->getData();
        $contentTemplate = $pageData->content_template;
        $this->_page->setContentTemplate($contentTemplate);
    }

    public function loadMetaData()
    {
        //load meta data
        $mdlMeta = new MetaData();
        $metaData = $mdlMeta->get($this->_page->getId());
        $this->_page->setMeta($metaData);
    }

    public function loadProperties()
    {
        $mdlProperties = new Properties();
        $properties = $mdlProperties->get($this->_page->getId());
        $this->_page->setProperties($properties);
    }

    public function loadRelatedPages()
    {

    }

    public function loadAttachments()
    {

    }

    public function setTitle()
    {
        $view = $this->_page->getView();
        $pageId = $this->_page->getId();

        //load the base site name
        $mdlSettings = new SiteSettings();
        $siteName = $mdlSettings->get('name');
        $separator = $mdlSettings->get('title_separator');

        //load the current page title
        $mdlPage = new Page();
        $title = $mdlPage->getTitle($pageId);

        //set the title
        $view->headTitle($siteName);

        if (is_array($title)) {
            foreach ($title as $pageTitle) {
                $view->headTitle($pageTitle);
            }
        }

        $view->headTitle()->setSeparator($separator);
    }

    public function googleIntegration()
    {
        $view = $this->_page->getView();
        $settings = new SiteSettings();
        $view->placeholder('google_verify')->set($settings->get('google_verify'));
        $view->placeholder('google_tracking')->set($settings->get('google_tracking'));
    }

    public function registerViewHelpers()
    {
        $view = $this->_page->getView();
        $helperDirs = DSF_Filesystem_Dir::getDirectories('./application/helpers');
        if (is_array($helperDirs)) {
            foreach ($helperDirs as $dir) {
                $view->addHelperPath('./application/helpers/' . $dir, 'DSF_View_Helper_' . ucfirst($dir));
            }
        }
    }
}