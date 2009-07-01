<?php
/**
 * @see Zend_Controller_Front
 */
require_once 'Zend/Controller/Front.php';

/**
 * Retrieve the request object
 *
 * @author
 * @license
 * @package
 * @subpackage
 * @copyright
 */
class DSF_View_Helper_General_GetRequest
{
    /**
     * Retrieve the request object
     *
     * @return Zend_Controller_Request_Abstract | null
     */
    public function getRequest()
    {
        return Zend_Controller_Front::getInstance()->getRequest();
    }
}
