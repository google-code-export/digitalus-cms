<?php
/**
 * Helper for embedding the response output of an existing Controller action.
 *
 * @package    Zend_View
 * @subpackage Helpers
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Digitalus_View_Helper_Cms_Controller
{
    public $defaultModule;
    public $dispatcher;
    public $front;
    public $request;
    public $response;

    /**
     * Constructor
     *
     * Initialize various helper objects and state.
     *
     * @return void
     */
    public function __construct()
    {
        $this->front         = Zend_Controller_Front::getInstance();
        $this->request       = clone $this->front->getRequest();
        $this->response      = clone $this->front->getResponse();
        $this->dispatcher    = clone $this->front->getDispatcher();
        $this->defaultModule = $this->front->getDefaultModule();
    }

    /**
     * Reset state
     *
     * @return void
     */
    public function resetObjects()
    {
        // Should probably create a 'clearUserParams()' method...
        $params = $this->request->getUserParams();
        foreach (array_keys($params) as $key) {
            $this->request->setParam($key, null);
        }

        $this->response->clearBody();
        $this->response->clearHeaders();
        $this->response->clearRawHeaders();
    }

    /**
     * Invoke a controller component
     *
     * @param  string $action Action name
     * @param  string $controller Controller name
     * @param  string $module Module name; defaults to default module name as registered in dispatcher
     * @param  array $params Additional parameters to pass in request object
     * @return string
     */
    public function controller($action, $controller, $module = null, array $params = array())
    {
        $this->resetObjects();
            if (null === $module) {
                $module = $this->defaultModule;
            }
        $this->request->setParams($params)
             ->setModuleName($module)
             ->setControllerName($controller)
             ->setActionName($action)
             ->setDispatched(true);

        $this->dispatcher->dispatch($this->request, $this->response);

        if (!$this->request->isDispatched() || $this->response->isRedirect()) {
                // forwards and redirects render nothing
                return '';
            }
        return $this->response->getBody();
    }
}