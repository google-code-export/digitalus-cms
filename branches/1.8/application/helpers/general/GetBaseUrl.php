<?php
/**
 * @see Zend_Controller_Front
 */
require_once 'Zend/Controller/Front.php';

/**
 * Retrieve the base url
 *
 * @author
 * @license
 * @package
 * @subpackage
 * @copyright
 */
class DSF_View_Helper_General_GetBaseUrl
{
    /**
     * Retrieve the base url
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return Zend_Controller_Front::getInstance()->getBaseUrl();
    }
}
