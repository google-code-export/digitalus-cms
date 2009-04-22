<?php
/**
 * @see Zend_Controller_Front
 */
require_once 'Zend/Controller/Front.php';

/**
 * Retrieve the response object
 *
 * @author
 * @license
 * @package
 * @subpackage
 * @copyright
 */
class DSF_View_Helper_GetResponse
{
    /**
     * Retrieve the response object
     *
     * @return Zend_Controller_Response_Abstract
     */
    public function getResponse()
    {
        return Zend_Controller_Front::getInstance()->getResponse();
    }
}
