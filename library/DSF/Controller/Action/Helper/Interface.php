<?php
/**
 * i got this code from zend.com
 * i cant remember the author, so if you know please let me know
 * so i can give credit ;)
 *
 */
class DSF_Controller_Action_Helper_InterfaceLoader
    extends Zend_Controller_Action_Helper_ViewRenderer
{

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
    public function __construct(Zend_View_Interface $view = null, array $options = array())
    {
        parent::__construct($view, $options);
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

        if ($request->getModuleName() != 'public' && $request->getControllerName() != 'public' && !$request->isXmlHttpRequest()) {
            //add the script path to admin
            $this->view->addScriptPath('./application/admin/views/scripts/assets');
            $this->view->addScriptPath('./templates/admin');

            $this->_loadAdminUser();
            $this->_loadAdminMenu();
            $this->_loadSidebar();
            $this->_loadMainForm();
            $this->_loadOptions();

            // render layout script and append to Response's body
            $layoutScript = $this->getLayoutScript();
            $layoutContent = $this->view->render($layoutScript);
        } else {
            $layoutContent = $this->view->render($this->view->actionScript);
        }
        $this->getResponse()->appendBody($layoutContent, $name);

        $this->setNoRender();
    }

    private function _buildAdminInterface($request)
    {

    }

    private function _loadAdminUser()
    {
        $this->_currentAdmin = $this->view->CurrentAdminUser();
        if ($this->_currentAdmin) {
            $this->view->placeholder('currentAdmin')->set($this->_currentAdmin);
        } else {
            $this->view->placeholder('currentAdmin')->set('');
        }
    }

    private function _loadAdminMenu()
    {
        if ($this->_currentAdmin) {
            $this->view->placeholder('adminMenu')->set($this->view->render($this->_pathToMenu));
        } else {
            $this->view->placeholder('adminMenu')->set('<ul></ul>');
        }
    }

    private function _loadSidebar()
    {
        $request = $this->getRequest();
        $path = str_replace('.phtml', '.sidebar.phtml', $this->_actionScript);
        $sidebarContent = $this->view->render($path);
        $this->view->placeholder('sidebar')->set($sidebarContent);
    }

    private function _loadOptions()
    {
        $path = str_replace('.phtml', '.options.phtml', $this->_actionScript);
        $sidebarContent = $this->view->render($path);
        $this->view->placeholder('sidebar')->set($sidebarContent);
    }

    private function _loadMainForm()
    {
        $mainForm = $this->view->render($this->_actionScript);
        $this->view->placeholder('mainForm')->set($mainForm);
    }
}