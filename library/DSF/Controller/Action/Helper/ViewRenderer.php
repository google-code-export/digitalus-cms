<?php
/**
 * i got this code from zend.com
 * i cant remember the author, so if you know please let me know
 * so i can give credit ;)
 *
 */
class DSF_Controller_Action_Helper_ViewRenderer 
    extends Zend_Controller_Action_Helper_ViewRenderer
{
    /**
     * Name of layout script to render. Defaults to 'site.phtml'.
     *
     * @var string
     */
    protected $_layoutScript = 'admin';
    protected $_pathToMenu = './admin-menu.phtml';
    protected $_actionScript = 'index.phtml';
    protected $_currentAdmin = null;
    
    
    /**
     * Constructor
     *
     * Set the viewSuffix to "phtml" unless a viewSuffix option is 
     * provided in the $options parameter.
     * 
     * @param  Zend_View_Interface $view 
     * @param  array $options 
     * @return void
     */
    public function __construct(Zend_View_Interface $view = null, 
                                array $options = array())
    {
        $config = Zend_Registry::get('config');
        if(isset($config->design->adminTemplate)){
            $this->_layoutScript = './' . $config->design->adminTemplate . '/index.phtml';
        }
        parent::__construct($view, $options);
    }
    
    /**
     * Set the layout script to be rendered.
     *
     * @param string $script
     */
    public function setLayoutScript($script)
    {
        $this->_layoutScript = $script;
    }
    
    /**
     * Retreive the name of the layout script to be rendered.
     *
     * @return string
     */
    public function getLayoutScript()
    {
        return $this->_layoutScript;
    }
    
    /**
     * Render the action script and assign the the view for use
     * in the layout script. Render the layout script and append
     * to the Response's body.
     *
     * @param string $script
     * @param string $name
     */
    public function renderScript($script, $name = null)
    {
        $this->_actionScript = $script;
        
        if (null === $name) {
            $name = $this->getResponseSegment();
        }
        
        //load the common helpers
        DSF_View_RegisterHelpers::register($this->view);
        
        //do not add the layout script to any of the public controllers
        //these are the controllers that render module parts
        
        $request = $this->getRequest();
            
        // assign action script name to view.
        $this->view->actionScript = $script;
        
        if($request->getModuleName() != 'public' && $request->getControllerName() != 'public' && !$request->isXmlHttpRequest())
        {
            //add the script path to admin
            $this->view->addScriptPath('./application/admin/views/scripts/assets');
            $this->view->addScriptPath('./templates/admin');
            
            $this->loadAdminUser();
            $this->loadAdminMenu();
            $this->loadSidebar();
            $this->loadMainForm();
            $this->loadOptions();
            
            // render layout script and append to Response's body
            $layoutScript = $this->getLayoutScript();        
            $layoutContent = $this->view->render($layoutScript);
        }else{
            $layoutContent = $this->view->render($this->view->actionScript);
        }
        $this->getResponse()->appendBody($layoutContent, $name);
        
        $this->setNoRender();
    }
    
    private function buildAdminInterface($request)
    {
        
    }
    
    private function loadAdminUser()
    {
        $this->_currentAdmin = $this->view->CurrentAdminUser();
        if($this->_currentAdmin){
            $this->view->placeholder('currentAdmin')->set($this->_currentAdmin);
        }else{
            $this->view->placeholder('currentAdmin')->set('');
        }
    }
    
    private function loadAdminMenu()
    {
        if($this->_currentAdmin){
            $this->view->placeholder('adminMenu')->set($this->view->render($this->_pathToMenu));
        }else{
            $this->view->placeholder('adminMenu')->set('<ul></ul>');
        }
    }
    
    private function loadSidebar()
    {
        $request = $this->getRequest();
		$path = str_replace('.phtml', '.sidebar.phtml', $this->_actionScript);
        $sidebarContent = $this->view->render($path);
        $this->view->placeholder('sidebar')->set($sidebarContent);
    }
    
    private function loadOptions()
    {
		$path = str_replace('.phtml', '.options.phtml', $this->_actionScript);
        $sidebarContent = $this->view->render($path);
        $this->view->placeholder('sidebar')->set($sidebarContent);
    }
    
    private function loadMainForm()
    {
        $mainForm = $this->view->render($this->_actionScript);
        $this->view->placeholder('mainForm')->set($mainForm);
    }
}