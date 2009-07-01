<?php
/**
 * @see Zend_Session_Namespace
 */
require_once 'Zend/Session/Namespace.php';

/**
 * Retrieve a session namespace object
 *
 * @author
 * @license
 * @package
 * @subpackage
 * @copyright
 */
class Digitalus_View_Helper_General_GetSession
{
    /**
     * Retrieve a (specific) session namespace object
     *
     * @return Zend_Session_Namespace
     */
    public function getSession($namespace = 'Default', $singleInstance = false)
    {
        return new Zend_Session_Namespace($namespace, $singleInstance);
    }
}
