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
 * @author      LowTower - lowtower@gmx.de
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Models
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: Group.php Mon Dec 24 20:38:38 EST 2007 20:38:38 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */

/**
 * @see Digitalus_Db_Table
 */
require_once 'Digitalus/Db/Table.php';

/**
 * User Group model
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */
class Model_Group extends Digitalus_Db_Table
{
    // These two roles are hardcoded and cannot be modified in the CMS backend
    /**
     * The role of the superuser -> maximum permissions
     */
    const SUPERUSER_ROLE = 'superadmin';
    /**
     * The role of the guest user -> minimum permissions
     */
    const GUEST_ROLE = 'guest';

    /**
     * the maximum lenght for group names (must correspond to length in database)
     */
    const GROUPNAME_LENGTH = 30;
    /**
     * the regex that the groupName will be checked against
     */
    const GROUPNAME_REGEX = '/^[0-9\p{L}]*$/u';
    /**
     * this is the error message that will be displayed if the groupName doesn't match the regex
     */
    const GROUPNAME_REGEX_NOTMATCH = 'Please only use alphanumeric characters!';

    /**
     * table name
     *
     * @var string
     */
    protected $_name = 'groups';

    public $primaryIndex = 'name';

    public function createGroup($groupName, $parent = null, $description = null, $aclResources = null)
    {
        $data = array(
            'name'          => $groupName,
            'description'   => $description,
            'acl_resources' => $aclResources,
        );
        // this is necessary because of the foreign key
        if (!empty($parent) && '' != $parent) {
            $data['parent'] = $parent;
        }
        if (!$this->groupExists($groupName)) {
            return $this->insert($data);
        }
        return false;
    }

    public function updateAclResources($groupName, $resourcesArray)
    {
        $data['acl_resources'] = serialize($resourcesArray);
        $where[] = $this->_db->quoteInto('name = ?', $groupName);
        return $this->update($data, $where);
    }

    public function getAclResources($role)
    {
        $select = $this->select();
        $select->from($this->_name, array('name', 'acl_resources'))
               ->where($this->_db->quoteInto('name = ?', $role));
        $role = $this->fetchRow($select);
        return unserialize($role->acl_resources);
    }

    /**
     * returns the complete group row for the currently logged in group
     * @return zend_db_row
     */
    public function getCurrentUserRole()
    {
        $currentUser = Digitalus_Auth::getIdentity();
        if (!empty($currentUser) && isset($currentUser->role)) {
            return $currentUser->role;
        }
    }

    /**
     * this function queries a groups permissions
     *
     * the resource should be in the module_controller_action format
     *
     * if strict = true then this requires an exact match
     * example: news_article != news_article_edit
     *
     * if strict = false then it will add wildcards
     * example: news_article == news_article_edit
     *
     * if group is not set then the query will be run on the current group
     *
     * @param string $resource
     * @param boolean $strict
     * @param integer $group
     * @return boolean
     */
    public function queryPermissions($resource, $strict = false, $groupName = null)
    {
        if ($groupName !== null) {
            $group = $this->find($groupName)->current();
            if (!$group) {
                return false;
            }
            $resources = $this->getAclResources($group);
        } else {
            $resources = $this->getCurrentGroupsAclResources();
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

    /**
     * returns a hash of the current groups
     */
    public function getGroupNamesArray($exclude = null)
    {
        $groupsArray = array();
        $select = $this->select();
        $select->from($this->_name, array('name', 'label'));
        $groups = $this->fetchAll($select);
        foreach ($groups as $group) {
            $groupsArray[$group->name] = array(
                'name'  => $group->name,
                'label' => $group->label,
            );
        }
        if (!empty($exclude) && '' != $exclude) {
            $exclude = (string)$exclude;
            unset($groupsArray[$exclude]);
        }
        return $groupsArray;
    }

    /**
     * returns a hash of the current groups
     */
    public function getGroupNamesParentsArray($exclude = null)
    {
        $groupsArray = array();
        $select = $this->select();
        $select->from($this->_name, array('name', 'parent'));
        $groups = $this->fetchAll($select);
        foreach ($groups as $group) {
            $groupsArray[$group->name]['name']   = $group->name;
            $groupsArray[$group->name]['parent'] = $group->parent;
        }
        if (!empty($exclude) && '' != $exclude) {
            $exclude = (string)$exclude;
            unset($groupsArray[$exclude]);
        }
        return $groupsArray;
    }

    public function copyPermissions($from, $to)
    {
        $fromGroup = $this->find($from)->current();
        $toGroup   = $this->find($to)->current();
        $toGroup->acl_resources = $fromGroup->acl_resources;
        return $toGroup->save();
    }

    /**
     * This function checks if a group already exists
     *
     * @param  string  $groupName  The name to check for
     * @param  string  $exclude   Groupnames to exclude from check
     * @return boolean
     */
    public function groupExists($groupName, $exclude = null)
    {
        $groupName = strtolower($groupName);
        if (!is_array($exclude)) {
            $exclude = array($exclude);
        }
        $groupNames = $this->getGroupNamesArray();
        if (in_array($groupName, $groupNames) && !in_array($groupName, $exclude)) {
            return true;
        }
        return false;
    }
}