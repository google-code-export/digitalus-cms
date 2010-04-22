<?php
/**
 * RenderModule helper
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
 * @category    Digitalus
 * @package     Digitalus_View
 * @subpackage  Helper
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id:$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * RenderModule helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper Digitalus_View_Helper_LoadModule
 * @uses        Digitalus_Uri
 */
class Digitalus_View_Helper_Cms_RenderModule extends Zend_View_Helper_Abstract
{
    /**
     * render a module page like news_showNewPosts
     */
    public function renderModule($moduleData, $defaultModule = null, $params = array())
    {
        if (!empty($moduleData) || $defaultModule != null) {
            if (!empty($moduleData)) {
                $xml = simplexml_load_string($moduleData);
            }

            if ($xml->module == 0 && $defaultModule != null) {
                $xml = simplexml_load_string($defaultModule);
            }
            if (is_object($xml)) {
                //build params
                foreach ($xml as $k => $v) {
                    $params[$k] = (string)$v;
                }
                $moduleParts = explode('_', $xml->module);

                if (is_array($moduleParts) && count($moduleParts) == 2) {
                    $name = $moduleParts[0];
                    $action = $this->_getAction($moduleParts);
                    return $this->view->loadModule($name, $action, $params);
                }
            }
        }
        return null;
    }

    protected function _getAction($moduleParts)
    {
        // Check whether uri params are given and set the action respectively
        $uri = new Digitalus_Uri();
        $uriParams = $uri->getParams();
        $name   = strtolower($moduleParts[0]);
        if (isset($uriParams['a']) && !empty($uriParams['a'])) {
            $action = $uriParams['a'];
        }
        if (isset($action) && !empty($action) && '' != $action && $this->_actionExists($name, $action)) {
            return $action;
        }
        return $moduleParts[1];
    }

    protected function _actionExists($moduleName, $action)
    {
        // action must only contain alphanumeric characters
        if (!Zend_Validate::is($action, 'Alnum')) {
            /**
             * @see Digitalus_View_Exception
             */
            require_once 'Digitalus/View/Exception.php';
            throw new Digitalus_View_Exception('only alphanumeric characters are allowed for the action');
        }
        // include PublicController to check whether the action exist
        // TODO: check if necessary
        require_once APPLICATION_PATH . '/modules/' . $moduleName . '/controllers/' . 'PublicController.php';
        $className  = 'Mod_' . ucfirst($moduleName) . '_PublicController';
        $actionName = $action . 'Action';
        if (method_exists($className, $actionName)) {
            return true;
        }
        return false;
    }
}