<?php 

/**
 * DSF CMS
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
 * @category   DSF CMS
 * @package   DSF_Core_Library
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: Error.php Tue Dec 25 21:29:34 EST 2007 21:29:34 forrest lyman $
 */

class DSF_View_Error
{
	/**
	 * the storage
	 *
	 * @var zend_session_namespace
	 */
    private $ns;
    
    /**
     * the errors stack
     *
     * @var array
     */
    private $errors;
    
    /**
     * set up the session namespace and load the errors stack
     *
     */
    function __construct()
    {
        $this->ns = new Zend_Session_Namespace('errors'); 
        if(isset($this->ns->errors)){
            $this->errors = $this->ns->errors;   
        }
    }
    
    /**
     * clear the errors stack
     *
     */
    function clear()
    {
        unset($this->errors);
        $this->updateNs();
    }
    
    /**
     * add an error to the stack
     *
     * @param string $error
     */
    function add($error)
    {
        $this->errors[] = $error;
        $this->updateNs();
    }
    
    /**
     * check to see if any errors are set
     *
     * @return bool
     */
    function hasErrors()
    {
        if(count($this->errors) > 0){
            return true;
        }
    }
    
    /**
     * get the errors stack
     *
     * @return string
     */
    function get()
    {
        return $this->errors;
    }
    
    /**
     * update the storage
     *
     */
    private function updateNs()
    {
        if(isset($this->errors)){
            $this->ns->errors = $this->errors;
        }else{
            unset($this->ns->errors);
        }
    }
}