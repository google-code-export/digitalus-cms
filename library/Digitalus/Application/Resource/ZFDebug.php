<?php
/**
 * This class provides a resource that setup the ZFDebug plugin
 *
 * @see http://code.google.com/p/zfdebug/
 *
 */
class Digitalus_Application_Resource_ZFDebug
    extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var const
     */
    const _NAMESPACE = 'ZFDebug';

    /**
     * @var boolean
     */
    protected $_init = false;

    /**
     * @var boolean
     */
    protected $_enabled = false;

    /**
     * @var array
     */
    protected $_params = array();

    /**
     * Set plugin options
     *
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->_params = $params;
    }

    /**
     * Return plugin options
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * Activate plugin
     *
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->_enabled = (boolean)$enabled;
    }

    /**
     * Return true iff plugin should be enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->_enabled;
    }

    /**
     * Defined by Zend_Application_Resource_Resource
     */
    public function init()
    {
        $this->initDebugPlugin();
    }

    /**
     * Initialize ZFDebug plugin
     */
    public function initDebugPlugin()
    {
        if (!$this->_init && $this->getEnabled()) {
            // execute once
            $this->_init = true;
            // plugin options
            $options = $this->getParams();

            // instantiate plugin
            $zfDebug = new ZFDebug_Controller_Plugin_Debug($options);

            // bootstrap database
            if (isset($options['plugins']['Database'])) {
                if ($this->getBootstrap()->hasPluginResource('db')) {
                    $this->getBootstrap()->bootstrap('db');
                }
            }
            // bootstrap cache
            if (isset($options['plugins']['Cache'])) {
                if ($this->getBootstrap()->hasPluginResource('cache')) {
                    $this->getBootstrap()->bootstrap('cache');
                    $cache = $this->getBootstrap()->getResource('cache');
                    $zfdebug->registerPlugin(new ZFDebug_Controller_Plugin_Debug_Plugin_Cache(array('backend' => $cache->getBackend())));
                }
            }
            // normalize base_path with realpath
            if (isset($options['plugins']['File']['base_path'])) {
                $options['plugins']['File']['base_path'] = realpath(
                    $options['plugins']['File']['base_path']
                );
            }
            // register namespace
            $autoloader = Zend_Loader_Autoloader::getInstance();
            $autoloader->registerNamespace(self::_NAMESPACE);

            // ensure frontcontroller is initializated
            $this->getBootstrap()->bootstrap('frontController');
            // add plugin to front controller
            $frontController = $this->getBootstrap()->getResource('frontController');
            $frontController->registerPlugin($zfDebug);
        }
    }
}