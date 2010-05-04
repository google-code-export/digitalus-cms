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
 * @package     Digitalus
 * @subpackage  Digitalus_Command
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id$
 */

class Digitalus_Command_CacheClear extends Digitalus_Command_Abstract
{

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * clears the cache
     * if the param key is set it will only clear the specified one
     */
    public function run($params = null)
    {
        if (Zend_Registry::isRegistered('cache')) {
            $cache = Zend_Registry::get('cache');
            if (isset($params['key'])) {
                $cache->clean($params['key']);
                $this->log($this->view->getTranslation('Cache cleared Key') . ' = ' . $params['key']);
            } else {
                $cache->clean(Zend_Cache::CLEANING_MODE_ALL);
                $this->log($this->view->getTranslation('Cache cleared'));
            }
        } else {
            $this->log($this->view->getTranslation('Error: Cache is not registered'));
        }

    }

    /**
     * returns details about the current command
     *
     */
    public function info()
    {
        $this->log($this->view->getTranslation('The cache clear function will either clear a specified key or all cache files if a key is not specified.'));
        $this->log($this->view->getTranslation('Params: key (string, optional)'));
    }
}