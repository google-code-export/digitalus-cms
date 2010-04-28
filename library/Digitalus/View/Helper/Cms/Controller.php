<?php
/**
 * Controller helper
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://digitalus-media.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@digitalus-media.com so we can send you a copy immediately.
 *
 * @author      Forrest Lyman
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_View
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: Controller.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * Controller helper
 *
 * Helper for embedding the response output of an existing Controller action.
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */
class Digitalus_View_Helper_Cms_Controller extends Zend_View_Helper_Abstract
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