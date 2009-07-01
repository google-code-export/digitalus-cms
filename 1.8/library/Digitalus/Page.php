<?php
class Digitalus_Page extends Digitalus_Model_Abstract
{
    // The page object stores all of its data in the params array
    protected $_params = array(
        'id'                 => null,
        'uri'                => null,
        'baseUrl'            => null,
        'data'               => null,
        'parents'            => null,
        'metaData'           => null,
        'properties'         => null,
        'language'           => null,
        'availableLanguages' => null,
        'content'            => null,
        'defaultContent'     => null,
        'contentTemplate'    => null,
        'design'             => null,
        'layout'             => null,
    );

    // These parameters are locked
    protected $_protectedParams = array();

    public $view;

    public function __construct($uri, $view = null)
    {
        $this->setParam('uri', $uri);
        $this->setView($view);
    }

    public function __wakeup()
    {
    }

    public function setParams($params)
    {
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                $this->setParam($key, $value);
            }
        }
    }

    public function setParam($key, $value, $protected = false)
    {
        if ($this->_isProtected($key)) {
            throw new Zend_Exception('Unable to set protected property (' . $key . ') in Digitalus_Page');
        } else {
            $this->_params[$key] = $value;
            if ($protected == true) {
                $this->_protectedParams[] = $key;
            }
        }
    }

    public function getParams()
    {
        return $this->_params;
    }

    public function getParam($key)
    {
        if (isset($this->_params[$key])) {
            return $this->_params[$key];
        }
#        $method = 'get' . ucfirst($key) . '()';
#        if (method_exists($this, $method)) {
#            return $this->$method();
#        }
    }

/*
    public function setId($id)
    {
        $this->setParam('id', $id);
    }

    public function getId()
    {
        return $this->getParam('id');
    }

    public function setUri($uri)
    {
        $this->setParam('uri', $uri);
    }

    public function getUri()
    {
        return $this->getParam('uri');
    }

    public function setBaseUrl($url)
    {
        $this->setParam('baseUrl', $url);
    }

    public function getBaseUrl()
    {
        return $this->getParam('baseUrl');
    }
*/
    public function setData($data)
    {
        $this->setParam('data', serialize($data));
    }

    public function getData()
    {
        return unserialize($this->getParam('data'));
    }
/*
    public function getParents()
    {
        return $this->getParam('parents');
    }

    public function setParents($parents)
    {
        $this->setParam('parents', $parents);
    }

    public function setMeta($metaData)
    {
        $this->setParam('metaData', $metaData);
    }

    public function getMeta()
    {
        return $this->getParam('metaData');
    }

    public function setProperties($properties)
    {
        $this->setParam('properties', $properties);
    }

    public function getProperties()
    {
        return $this->getParam('properties');
    }

    public function setLanguage($language)
    {
        $this->setParam('language', $language);
    }
*/
    public function getLanguage()
    {
        if ($this->_hasProperty('language')) {
            return $this->getParam('language');
        } else {
            return null;
        }

    }
/*
    public function setAvailableLanguages($languages)
    {
        $this->setParam('availableLanguages', $languages);
    }
*/
    public function getAvailableLanguages()
    {
        if ($this->_hasProperty('availableLanguages')) {
            return $this->getParam('availableLanguages');
        } else {
            return null;
        }

    }
/*
    public function setContent($content)
    {
        $this->setParam('content', $content);
    }

    public function setDefaultContent($content)
    {
        $this->setParam('defaultContent', $content);
    }
*/
    public function getContent($key = null, $useDefault = true)
    {
        $content = $this->getParam('content');
        $defaultContent = $this->getParam('defaultContent');
        if ($useDefault && is_array($defaultContent)) {
            foreach ($defaultContent as $k => $v) {
                if (!empty($v) && empty($content[$k])) {
                    $content[$k] = $v;
                }
            }
        }

        if ($key !== null) {
            return $content->$key;
        } else {
            return $content;
        }

    }
/*
    public function setContentTemplate($contentTemplate)
    {
        $this->setParam('contentTemplate', $contentTemplate);
    }

    public function getContentTemplate()
    {
        return $this->getParam('contentTemplate');
    }

    public function setDesign($design)
    {
        $this->setParam('design', $design);
    }
    public function getDesign()
    {
        $design = new Model_Design();
        $design->setDesign($this->getParam('design'));
        return $design;
    }
    public function setLayout($layout)
    {
        $this->setParam('layout', $layout);
    }

    public function getLayout()
    {
        return $this->getParam('layout');
    }
*/
    public function getView()
    {
        return $this->view;
    }

    public function setView(Zend_View $view = null)
    {
       if ($view == null) {
            $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
            if (null === $viewRenderer->view) {
                $viewRenderer->initView();
            }
            $this->view = $viewRenderer->view;
        } else {
            $this->view = $view;
        }
    }

}