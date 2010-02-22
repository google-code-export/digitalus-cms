<?php
/**
 * Digitalus CMS
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
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Models
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: User.php Mon Dec 24 20:38:38 EST 2007 20:38:38 forrest lyman $
 */

class Model_User extends Digitalus_Db_Table
{
    const SUPERUSER_ROLE = 'superadmin';

    /**
     * table name
     *
     * @var string
     */
    protected $_name = 'users';

    public function updatePassword($id, $password, $confirmationRequire = true, $confirmation = null)
    {
        $person = $this->find($id)->current();
        if ($person) {
            if ($confirmationRequire == true) {
                if ($confirmation != $password) {
                    return false;
                }
            }
            $person->password = md5($password);

            $result = $person->save();
            return $result;
        } else {
            return false;
        }
    }

    public function updateAclResources($userId, $resourceArray)
    {
        $data['acl_resources'] = serialize($resourceArray);
        $where[] = $this->_db->quoteInto('id = ?', $userId);
        return $this->update($data, $where);
    }

    public function getAclResources($userRowset)
    {
        return unserialize($userRowset->acl_resources);
    }

    /**
     * returns the complete user row for the currently logged in user
     * @return zend_db_row
     */
    public function getCurrentUser()
    {
        $currentUser = Digitalus_Auth::getIdentity();
        if ($currentUser) {
            return $this->find($currentUser->id)->current();
        }
    }


    public function getCurrentUsersAclResources()
    {
        $currentUser = $this->getCurrentUser();
        if ($currentUser) {
            return $this->getAclResources($currentUser);
        }
    }

    public function getCurrentUsersModules()
    {
        return $this->getUsersModules($this->getCurrentUser());
    }

    public function getUsersModules($userRowset)
    {
        $modules = null;
        $user = $this->getCurrentUser();
        if ($user->role == Model_User::SUPERUSER_ROLE) {
            //the superadmin has access to all of the modules
            $front = Zend_Controller_Front::getInstance();
            $ctlPaths = $front->getControllerDirectory();
            foreach ($ctlPaths as $module => $path) {
                if (substr($module, 0, 4) == 'mod_') {
                    $modules[] = str_replace('mod_', '', $module);
                }
            }
        } else {
            $resources = $this->getAclResources($userRowset);
            if (is_array($resources)) {
                foreach ($resources as $k => $v) {
                    if (1 == $v ) {
                        $parts = explode('_', $k);
                        if ('mod' == $parts[0]) {
                            $key = $parts[1];
                            $modules[$key] = $key;
                        }
                    }
                }
            }
        }
        if (is_array($modules)) {
            return $modules;
        }
    }

    /**
     * this function queries a users permissions
     *
     * the resource should be in the module_controller_action format
     *
     * if strict = true then this requires an exact match
     * example: news_article != news_article_edit
     *
     * if strict = false then it will add wildcards
     * example: news_article == news_article_edit
     *
     * if user is not set then the query will be run on the current user
     *
     * @param string $resource
     * @param boolean $strict
     * @param integer $user
     * @return boolean
     */
    public function queryPermissions($resource, $strict = false, $userId = null)
    {
        if ($userId !== null) {
            $user = $this->find($userId)->current();
            if (!$user) {
                return false;
            }
            $resources = $this->getAclResources($user);
        } else {
            $resources = $this->getCurrentUsersAclResources();
        }

        if (is_array($resources)) {
            if ($strict) {
                if (array_key_exists($resource, $resources) && 1 == $resources[$resource]) {
                    return true;
                }
            } else {
                $len = strlen($resource);
                foreach ($resources as $r => $v) {
                    if (1 == $v && $resource == substr($r, 0, $len)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function getUserById($id, $format = null)
    {
        $where[] = $this->_db->quoteInto('id = ?', $id, 'INTEGER');
        $user = $this->fetchRow($where);
        switch ((string)strtolower($format)) {
            case 'firstname':
                return $user->first_name;
            case 'lastname':
                return $user->last_name;
            default:
                return $user->first_name . ' ' . $user->last_name;
        }
    }

    public function getUserByUsername($userName)
    {
        $where[] = $this->_db->quoteInto('email = ?', $userName);
        return $this->fetchRow($where);
    }

    public function getUserByOpenId($openId)
    {
        $where[] = $this->_db->quoteInto('openid = ?', $openId);
        return $this->fetchRow($where);
    }

    /**
     * @since 0.8.7
     *
     * returns a hash of the current users
     * their id is the key and their first_name . ' ' . last_name is the value
     *
     */
    public function getUserNamesArray()
    {
        $users = $this->fetchAll();
        foreach ($users as $user) {
            $usersArray[$user->id] = $user->first_name . ' ' . $user->last_name;
        }
        return $usersArray;
    }

    public function copyPermissions($from, $to)
    {
        $fromUser = $this->find($from)->current();
        $toUser = $this->find($to)->current();
        $toUser->acl_resources = $fromUser->acl_resources;
        return $toUser->save();
    }

    /**
     * This function checks if a user already exists
     *
     * @param  string  $userName  The username to check for
     * @return boolean
     */
    public function userExists($userName)
    {
        $userName = strtolower($userName);

        $where[] = $this->_db->quoteInto('LOWER(username) = ?', $userName);
        $result = $this->fetchAll($where, null, 1);
        if ($result->count() > 0) {
            return true;
        }
        return false;
    }

    /**
     * This function checks if a user has already been activated
     *
     * @param  int $userId The user id to check
     * @return boolean
     */
    public function isActive($userId)
    {
        $where[] = $this->_db->quoteInto('id = ?', $userId, 'INTEGER');
        $where[] = $this->_db->quoteInto('active = ?', 1, 'TINYINT');
        $result = $this->fetchAll($where, null, 1);
        if ($result->count() > 0) {
            return true;
        }
        return false;
    }

    /**
     * This function activates a user
     *
     * @param  int $userId The user id to activate
     * @return int Number of rows updated
     */
    public function activate($userId)
    {
        $data['active'] = 1;
        $where[] = $this->_db->quoteInto('id = ?', $userId, 'INTEGER');
        return $this->update($data, $where);
    }

    /**
     * This function deactivates a user
     *
     * @param  int $userId The user id to deactivate
     * @return int Number of rows updated
     */
    public function deactivate($userId)
    {
        $data['active'] = 0;
        $where[] = $this->_db->quoteInto('id = ?', $userId, 'INTEGER');
        return $this->update($data, $where);
    }
}