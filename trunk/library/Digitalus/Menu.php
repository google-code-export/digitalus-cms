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
 * @author      Lowtower - lowtower@gmx.de
 * @category    Digitalus CMS
 * @package     Digitalus
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.8.0
 */

/**
 * Digitalus Menu Class
 *
 * @author      Lowtower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.8
 */
class Digitalus_Menu extends Digitalus_Abstract
{
    const CACHE_ID = 'MenuChildren';

    public    $pages = array();
    protected $_parentId;
    protected $_identity;
    protected $_cache;

    /**
     * this function sets up then loads the menu
     *
     * @param int $parentId
     * @param int $levels
     */
    public function __construct($parentId = 0)
    {
        $this->setView();
        $this->_initCache();
        $this->_identity = Digitalus_Auth::getIdentity();
        $this->_parentId = $parentId;
        // check whether Zend_Navigation is already registered
        if (!Zend_Registry::isRegistered('Zend_Navigation')) {
            $this->_load();
        }
    }

    /**
     * this function loads the current menu and is run automatically by the constructor
     *
     */
    protected function _load()
    {
        $cache    = Zend_Registry::get('cache');
        $children = $cache->load(self::CACHE_ID);

        $mdlMenu  = new Model_Menu();
        if ($children === false) {
            $children = $mdlMenu->getChildren($this->_parentId);
            $cache->save($children, self::CACHE_ID);
        }
        if ($children != null && $children->count() > 0) {
            foreach ($children as $child) {
                $this->pages[] = new Digitalus_Menu_Item(null, $child);
            }
            $container = new Zend_Navigation($this->pages);

            // set container, acl and role for view helper
            $acl = new Digitalus_Acl();
            $this->view->navigation($container);
            $this->view->navigation()->setAcl($acl);
            $this->view->navigation()->setRole($this->_identity->role);
        }
        // write Zend_Navigation into registry
        Zend_Registry::set('Zend_Navigation', $container);
    }

    protected function _initCache()
    {
        $this->_cache = Zend_Registry::get('cache');
    }

    public static function cleanCacheByTag($tag = self::CACHE_ID)
    {
        $cache = Zend_Registry::get('cache');
        return $cache->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_TAG,
            array($tag)
        );
    }

    public static function cleanAllCache()
    {
        $cache = Zend_Registry::get('cache');
        return $cache->clean(Zend_Cache::CLEANING_MODE_ALL);
    }
}