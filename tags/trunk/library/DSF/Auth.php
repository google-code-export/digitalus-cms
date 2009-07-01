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
 * @version    $Id: Auth.php Tue Dec 25 21:40:32 EST 2007 21:40:32 forrest lyman $
 */

class DSF_Auth
{
    /**
     * auth adapter
     *
     * @var zend_auth_adapter
     */
    private $_authAdapter;

    private $_dbAdapter;

    /**
     * the passed username
     *
     * @var string
     */
    private $_username;

    /**
     * the passed password
     *
     * @var string
     */
    private $_password;

    /**
     * the user session storage
     *
     * @var zend_session_namespace
     */
    private $_storage;

    /**
     * the table that contains the user credentials
     *
     * @var string
     */
    protected  $_userTable = "users";

    /**
     * the indentity column
     *
     * @var string
     */
    protected  $_identityColumn = "email";

    /**
     * the credential column
     *
     * @var string
     */
    protected  $_credentialColumn = "password";
    
    const USER_NAMESPACE = 'adminUser';
    
    protected $_resultRowColumns = array('id', 'user_group_id', 'first_name', 'last_name', 'email', 'role', 'acl_resources');
    
    


    /**
     * build the login request
     *
     * @param string $username
     * @param string $password
     */
    public function __construct($username = null, $password = null)
    {
        //set up the db authentication
        // zend auth uses FETCH_ASSOC for the fetchmode
        $this->_dbAdapter = clone (Zend_Db_Table::getDefaultAdapter());
        $this->_dbAdapter->setFetchMode(zend_db::FETCH_ASSOC );

        $this->_authAdapter = new Zend_Auth_Adapter_DbTable($this->_dbAdapter, $this->_userTable, $this->_identityColumn, $this->_credentialColumn);

        $this->_username = $username;
        $this->_password = md5($password);

        //set up storage
        // @todo: i can not get zend to persist the identities for some reason .. figure out why
        $this->_storage = new Zend_Session_Namespace(self::USER_NAMESPACE);
    }

    /**
     * authenticate the request
     *
     * @return zend_auth_response
     */
    public function authenticate()
    {
        //authenticate the user
        $this->_authAdapter->setIdentity($this->_username);
        $this->_authAdapter->setCredential($this->_password);

        $result = $this->_authAdapter->authenticate();

        if ($result->isValid()) {
            //save the user and return the result
            $row = $this->_authAdapter->getResultRowObject($this->_resultRowColumns);
            $this->_storage->user = $row;
            return $result;
        }
    }

    /**
     * get the current user identity if it exists
     *
     * @return zend_auth_response
     */
    public static function getIdentity()
    {
        $storage = new Zend_Session_Namespace(self::USER_NAMESPACE);
        if (isset($storage->user)) {
          return $storage->user;
        }
    }

    /**
     * destroys the current user session
     *
     */
    public static function destroy()
    {
        $storage = new Zend_Session_Namespace(self::USER_NAMESPACE);
        $storage->unsetAll();
    }

}