<?php
class Digitalus_Builder_Action_Page extends Digitalus_Builder_Abstract
{
    public function appendUriParams()
    {
        $uri = new Digitalus_Uri();
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
        $mdlPage = new Model_Page();
        $uri = $this->_page->getUri();
        $pointer = $mdlPage->fetchPointer($uri);
        if ($pointer > 0) {
            $this->_page->setId($pointer);
        } else {
            //this needs to be refactored so the error controller can handle it
            $front = Zend_Controller_Front::getInstance();
            $response = $front->getResponse();
            $response->setRawHeader('HTTP/1.1 404 Not Found');
            require_once 'Digitalus/Builder/Exception.php';
            throw new Digitalus_Builder_Exception($this->view->getTranslation('The page you requested was not found. Digitalus CMS could not locate the error page either.'));
        }
    }

    public function setParents()
    {
        $mdlPage = new Model_Page();
        $parents = $mdlPage->getParents($this->_page->getId());
        if (is_array($parents)) {
            $this->_page->setParents($parents);
        }
    }

    public function loadData()
    {
        $mdlPage = new Model_Page();
        $row = $mdlPage->find($this->_page->getId())->current();
        if ($row) {
            $this->_page->setData($row);
        }
    }

    public function setLanguage()
    {
        $language           = $this->_page->getParam('language');
        $availableLanguages = Digitalus_Language::getAvailableLanguages();

        if (!empty($language) && key_exists($language, $availableLanguages)) {
            Digitalus_Language::setLanguage($language);
            $this->_page->setLanguage($language);
        } else {
            $this->_page->setLanguage(Digitalus_Language::getLanguage());
        }
    }

    public function setAvailableLanguages()
    {
        $page             = new Model_Page();
        $availableContent = $page->getVersions($this->_page->getId());
        $this->_page->setAvailableLanguages($availableContent);
    }

    public function loadContent()
    {
        $mdlPage = new Model_Page();
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
        $mdlMeta = new Model_MetaData();
        $metaData = $mdlMeta->get($this->_page->getId());
        $this->_page->setMetaData($metaData);
    }

    public function loadProperties()
    {
        $mdlProperties = new Model_Properties();
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
        $mdlSettings = new Model_SiteSettings();
        $siteName = $mdlSettings->get('name');
        $separator = $mdlSettings->get('title_separator');

        //load the current page title
        $mdlPage = new Model_Page();
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

    public function setMetaData()
    {
        $view = $this->_page->getView();
        $pageId = $this->_page->getId();

        //start the meta description and keywords with the site name
        $mdlSettings = new Model_SiteSettings();
        $siteName = $mdlSettings->get('name');
        $metaDescription[] = $siteName;
        $metaKeywords[] = $siteName;


        //add the base settings
        $metaDescription[] = $mdlSettings->get('meta_description');
        $metaKeywords[] = $mdlSettings->get('meta_keywords');

        //next add all of the page titles
        $mdlPage = new Model_Page();
        $title = $mdlPage->getTitle($pageId);
        if (is_array($title)) {
            $metaDescription[] = implode(',', $title);
            $metaKeywords[] = implode(',', $title);
        }

        //now add the page specific settings
        $mdlMeta = new Model_MetaData();
        $metaData = $mdlMeta->asArray($pageId);

        if (!empty($metaData['meta_description'])) {
            $metaDescription[] = (string)$metaData['meta_description'];
        }

        if (!empty($metaData['keywords'])) {
            $metaKeywords[] =  (string)$metaData['keywords'];
        }

        //now set the view placeholder
        $view->headMeta()->appendName('description', implode(',', $metaDescription));
        $view->headMeta()->appendName('keywords', implode(',', $metaKeywords));
    }

    public function googleIntegration()
    {
        $view = $this->_page->getView();
        $settings = new Model_SiteSettings();
        $view->placeholder('google_verify')->set($settings->get('google_verify'));
        $view->placeholder('google_tracking')->set($settings->get('google_tracking'));
    }

    public function registerViewHelpers()
    {
        $view = $this->_page->getView();
        $helperDirs = Digitalus_Filesystem_Dir::getDirectories('./application/helpers');
        if (is_array($helperDirs)) {
            foreach ($helperDirs as $dir) {
                $view->addHelperPath('./application/helpers/' . $dir, 'Digitalus_View_Helper_' . ucfirst($dir));
            }
        }
    }
}