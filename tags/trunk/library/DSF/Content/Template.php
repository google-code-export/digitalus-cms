<?php
class DSF_Content_Template
{
    const PROPERTIES_FILENAME = 'properties.xml';
    const FORM_FILENAME = 'form.php';
    const ALL_TEMPLATES = 'all';
    protected $_folder;
    protected $_template;
    protected $_templatePath;
    public $properties;

    public function __construct($folder, $template, $templatePath)
    {
        $this->_folder = $folder;
        $this->_template = $template;
        $this->_templatePath = $templatePath;
        if (!empty($folder) && !empty($template)) {
            $this->_loadProperties();
        }
    }

    public function getAllowedChildTemplates()
    {
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
            $loader = new DSF_Content_Template_Loader();
            return $loader->getTemplates();
        }
    }

    public function getForm()
    {
        $pathToForm = $this->_templatePath . '/' . $this->_folder . '/' . $this->_template . '/' . self::FORM_FILENAME ;
        require_once($pathToForm);
        $formClass = ucfirst($this->_folder) . '_' . ucfirst($this->_template) . '_Form';
        return new $formClass();
    }

    public function render($content, $viewInstance = null)
    {
        if ($viewInstance == null) {
            $view = new Zend_View();
        } else {
            $view = $viewInstance;
        }
        //load helpers for this view instance
        DSF_View_RegisterHelpers::register($view);
        $view->content = $content;
        $view->addScriptPath($this->_templatePath);
        $templateScript = $this->_folder . '/' . $this->_template . '/template.phtml';
        return $view->render($templateScript);

    }

    protected function _loadProperties()
    {
        $path = $this->_templatePath . '/' . $this->_folder . '/' . $this->_template . '/' . self::PROPERTIES_FILENAME;
        $this->_properties = new Zend_Config_Xml($path);
    }

}