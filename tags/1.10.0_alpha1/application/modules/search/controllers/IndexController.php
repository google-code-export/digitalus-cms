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
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id:$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_Controller_Action
 */
require_once 'Zend/Controller/Action.php';

/**
 * Search Module Index Controller of Digitalus CMS
 *
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Controllers
 * @version     $Id:
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */
class Mod_Search_IndexController extends Digitalus_Controller_Action
{
    /**
     * Initialize the action
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        $this->view->breadcrumbs = array(
           $this->view->getTranslation('Modules') => $this->baseUrl . '/admin/module',
           $this->view->getTranslation('Search') => $this->baseUrl . '/mod_search'
        );
        $this->view->toolbarLinks[$this->view->getTranslation('Add to my bookmarks')] = $this->baseUrl . '/admin/index/bookmark'
            . '/url/mod_search'
            . '/label/' . $this->view->getTranslation('Module') . ':' . $this->view->getTranslation('Search');
    }

    /**
     * The default action
     *
     * Checks the permissions of the index directory
     *
     * @return void
     */
    public function indexAction()
    {
        // Check whether index directory is writeable
        $this->_isIndexWriteable();
    }

    /**
     * Rebuild action
     *
     * @return void
     */
    public function rebuildAction()
    {
        //this can take a lot of time
        set_time_limit(0);
        $properties = Digitalus_Module_Property::load('mod_search');
        //create the index
        $index = Zend_Search_Lucene::create($properties->pathToIndex);

        $adapters = $properties->adapters;
        foreach ($adapters as $adapter) {
            require_once $adapter->filepath;
            $className = $adapter->classname;
            $adapterObj = new $className();
            $pages = $adapterObj->getPages();
            if (is_array($pages) && count($pages) > 0) {
                foreach ($pages as $page) {
                    $index->addDocument($page->asLuceneDocument());
                }
            }
        }
        $index->optimize();
        $this->_forward('index');
        echo '<p><strong>' . $this->view->getTranslation('The search index was rebuilt successfully!') . '</strong></p><br />';
    }

    /**
     * Check whether the index directory is writeable
     *
     * @return boolean true|false
     */
    protected function _isIndexWriteable()
    {
        $indexPath = APPLICATION_PATH . '/modules/search/data/index';
        if (!file_exists($indexPath) || !is_writeable($indexPath)) {
            $this->view->errorMessage = 'For the search module to work properly, the index directory must be writeable! Please check the permissions of this directory:';
            $this->view->indexPath = $indexPath;
            return false;
        }
        return true;
    }

}