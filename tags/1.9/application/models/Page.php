<?php
require_once 'application/models/ContentNode.php';

class Model_Page extends Digitalus_Db_Table
{
    protected $_name = 'pages';
    protected $_namespace = 'content';
    protected $_defaultTemplate = 'default';
    protected $_defaultPageName = 'New Page';
    protected $_ignoredFields = array('update', 'version'); //these are the fields that are not saved as nodes

    const PUBLISH_ID   =  1;
    const UNPUBLISH_ID = 11;
    const ARCHIVE_ID   = 21;

    const PUBLISH_STATUS   = 'published';
    const UNPUBLISH_STATUS = 'unpublished';
    const ARCHIVE_STATUS   = 'archived';

    protected $_statusTemplates = array(
        self::PUBLISH_ID   => self::PUBLISH_STATUS,
        self::UNPUBLISH_ID => self::UNPUBLISH_STATUS,
        self::ARCHIVE_ID   => self::ARCHIVE_STATUS,
    );

    public function getContent($uri, $version = null)
    {
        if ($version == null) {
            $version = $this->getDefaultVersion();
        }

        $uriObj = new Digitalus_Uri($uri);
        $pointer = $this->fetchPointer($uriObj->toArray());
        $node = new Model_ContentNode();
        //fetch the content nodes
        return $node->fetchContentArray($pointer, null, null, $version);
    }

    public function getCurrentUsersPages($order = null, $limit = null)
    {
        $user = new Model_User();
        $currentUser = $user->getCurrentUser();
        if ($currentUser) {
            $select = $this->select();
            $select->where('author_id = ?', $currentUser->id);
            $select->where('namespace = ?', $this->_namespace);
            if ($order != null) {
                $select->order($order);
            }
            if ($limit != null) {
                $select->limit($limit);
            }
            $pages = $this->fetchAll($select);
            if ($pages->count() > 0) {
                return $pages;
            } else {
                return null;
            }
        } else {
            throw new Zend_Exception('There is no user logged in currently');
        }
    }

    public function createPage($pageName, $parentId = 0, $contentTemplate = null, $showOnMenu = null, $publishPage = null)
    {
        if (empty($pageName)) {
            $pageName = $this->_defaultPageName;
        }

        if ($contentTemplate == null) {
            $contentTemplate = $this->_defaultTemplate;
        }

        if ($showOnMenu !== null) {
            if ($showOnMenu == true) {
                $makeMenuLinks = 1;
            } else {
                $makeMenuLinks = 0;
            }
        } else {
            $settings = new Model_SiteSettings();
            $makeMenuLinks = $settings->get('add_menu_links');
        }

        // get current time to ensure create and publish date are exactly the same
        $time = time();

        if ($publishPage === null) {
            $settings = new Model_SiteSettings();
            $publishPage = $settings->get('publish_pages');
        }
        if ($publishPage == true) {
            $publishDate  = $time;
            $publishLevel = self::PUBLISH_ID;
        } else {
            $publishDate  = 'NULL';
            $publishLevel = self::UNPUBLISH_ID;
        }

        $u = new Model_User();
        $user = $u->getCurrentUser();
        if ($user) {
            $userId = $user->id;
        } else {
            $userId = 0;
        }

        //first create the new page
        $data = array(
            'namespace'        => $this->_namespace,
            'create_date'      => $time,
            'publish_date'     => $publishDate,
            'publish_level'    => $publishLevel,
            'author_id'        => $userId,
            'name'             => $pageName,
            'content_template' => $contentTemplate,
            'parent_id'        => $parentId,
            'show_on_menu'     => $makeMenuLinks
        );
        $this->insert($data);
        $id = $this->_db->lastInsertId();

        $this->_flushCache();

        //return the new page
        return $this->find($id)->current();
    }

    public function getTemplate($pageId)
    {
        $currentPage = $this->find($pageId)->current();
        if ($currentPage) {
            return $currentPage->content_template;
        }
    }

    public function open($pageId, $version = null)
    {
        if ($version == null) {
            $version = $this->getDefaultVersion();
        }

        $currentPage = $this->find($pageId)->current();
        if ($currentPage) {
            $page = new stdClass();
            $page->page = $currentPage;

            $node = new Model_ContentNode();

            //fetch the content nodes
            $page->content = $node->fetchContentArray($pageId, null, null, $version);
            $page->defaultContent = $node->fetchContentArray($pageId, null, null, $this->getDefaultVersion());

            return $page;
        } else {
            return null;
        }
    }

    public function pageExists(Digitalus_Uri $uri)
    {
        if ($this->_fetchPointer($uri->toArray(), 0) || $uri == null) {
            return true;
        } else {
            return false;
        }
    }

    public function edit($pageArray)
    {
        $pageId = isset($pageArray['id']) ? $pageArray['id'] : $pageArray['page_id'];
        if (!$pageId) {
            throw new Zend_Exception('Invalid Page: No key found for id');
        } else {
            unset($pageArray['page_id']);
            $name = $pageArray['name'];
            unset($pageArray['name']);

            //save the page details
            $currentPage = $this->find($pageId)->current();
            if (!$currentPage) {
                throw new Zend_Exception('Could not load page');
            } else {
                $currentPage->name = $name;
                $currentPage->save();
            }

            //page version
            if (isset($pageArray['version']) && !empty($pageArray['version'])) {
                $version = $pageArray['version'];
            } else {
                $siteSettings = new Model_SiteSettings();
                $version = $this->getDefaultVersion();
            }
            //update the content
            $contentNode = new Model_ContentNode();

            if (count($pageArray) > 0) {
                foreach ($pageArray as $node => $content) {
                    if (!in_array($node, $this->_ignoredFields)) {
                        $contentNode->set($pageId, $node, $content, $version);
                    }
                }
            }

            $this->_flushCache();
            return $this->open($pageId, $version);
        }
    }

    public function setNamespace($namespace)
    {
        $this->_namespace = $namespace;
    }

    public function getVersions($pageId)
    {
        $node = new Model_ContentNode();
        return $node->getVersions('page_' . $pageId);
    }

    public function getDefaultVersion()
    {
        $settings = new Model_SiteSettings();
        return $settings->get('default_language');
    }

    /**
     * returns the content type of the selected page
     *
     * @param  int $pageId
     * @return string
     */
    public function getcontentTemplate($pageId)
    {
        $page = $this->find($pageId)->current();
        if ($page) {
            return $page->content_template;
        } else {
            return null;
        }
    }

    public function getTitle($pageId)
    {
        $titleParts[] = $this->getPageTitle($pageId);
        $parents = $this->getParents($pageId);
        if ($parents) {
            foreach ($parents as $parent) {
                $titleParts[] = $this->getPageTitle($parent->id);
            }
        }

        return array_reverse($titleParts);
    }

    public function getPageTitle($pageId)
    {
        $mdlMeta = new Model_MetaData();
        $metaData = $mdlMeta->asArray($pageId);
        if (!empty($metaData['page_title'])) {
            return $metaData['page_title'];
        } else {
            $page = $this->find($pageId)->current();
            return $page->name;
        }
    }

    public function deletePageById($pageId)
    {

        $this->_flushCache();
        $where[] = $this->_db->quoteInto('id = ?', $pageId);
        $this->delete($where);

        //delete content nodes
        unset($where);
        $mdlNodes = new Model_ContentNode();
        $where[] = $this->_db->quoteInto('parent_id = ?', 'page_' . $pageId);
        $mdlNodes->delete($where);

        //delete meta data
        $mdlMeta = new Model_MetaData();
        $mdlMeta->deleteByPageId($pageId);
    }

    public function setDesign($pageId, $designId)
    {
        $page = $this->find($pageId)->current();
        if ($page) {
            $page->design = $designId;
            $page->save();
            return true;
        } else {
            return false;
        }
    }

    public function setContentTemplate($pageId, $template)
    {
        $page = $this->find($pageId)->current();
        if ($page) {
            $page->content_template = $template;
            $page->save();
            return true;
        } else {
            return false;
        }
    }

    public function getDesign($pageId)
    {
        $page = $this->find($pageId)->current();
        $designId = $page->design;
        $mdlDesign = new Model_Design();
        $mdlDesign->setDesign($designId);
        return $mdlDesign;
    }

    public function getPagesByDesign($designId)
    {
        $select = $this->select();
        $select->where('design = ?', $designId);
        $select->order('name');
        return $this->fetchAll($select);
    }

    /**
     * this function sets the related pages for a given page
     *
     * @param  int $pageId
     * @param  array $relatedPages
     * @return boolean
     */
    public function setRelatedPages($pageId, $relatedPages)
    {
        if (is_array($relatedPages)) {
            $data = array(
                'related_pages' => implode(',', $relatedPages)
            );
            $where[] = $this->_db->quoteInto('id = ?', $pageId);
            return $this->update($data, $where);
        }
    }

    /**
     * this function returns an array of the ids of the pages which are related to $pageId
     * if asObject is set to true it will return a rowset instead
     *
     * @param  int $pageId
     * @param  boolean $asObject
     * @return mixex
     */
    public function getRelatedPages($pageId, $asObject = false)
    {
        $row = $this->find($pageId)->current();
        if ($row) {
            $pageArray = explode(',', $row->related_pages);
            if (is_array($pageArray) && count($pageArray) > 0) {
                if ($asObject) {
                    //return the rowset
                    return $this->find($pageArray);
                } else {
                    //return the array
                    return $pageArray;
                }
            }
        }
    }

    /**
     * Fetch pointer action
     *
     * The following function handle the site tree
     *
     * @param  string  $uri
     * @return string  $page
     */
    public function fetchPointer($uri)
    {
        $settings = new Model_SiteSettings();
        $isOnline = $settings->get('online');
        if ($isOnline == 0) {
            return $this->getOfflinePage();
        } else {
            if (!is_array($uri)) {
                //return home page
                $id = $this->getHomePage();
            } else {
                $id = $this->_fetchPointer($uri);
            }

            //test the pointer - also against publishing state
            $row = $this->find($id)->current();
            if ($row && $this->isPublished($row->id) == self::PUBLISH_ID) {
               return $row->id;
            } else {
                return $this->get404Page();
            }
        }
    }

    /**
     * this function returns the children of a selected page
     * you can pass it a page id (integer) or a page object
     * you can optionally pass it an array of where clauses
     *
     * @param  mixed  $page
     * @param  array  $where
     * @param  string $order
     * @param  string $limit
     * @param  string $offset
     * @return Zend_Db_Table_Rowset
     */
    public function getChildren($page, $where = array(), $order = null, $limit = null, $offset = null)
    {
        $id = $this->_getPageId($page);

        $where[] = $this->_db->quoteInto('parent_id = ?', $id);

        if ($order == null) {
            $order = 'position ASC';
        }

        $result = $this->fetchAll($where, $order, $limit, $offset);
        if ($result->count() > 0) {
            return $result;
        } else {
            return null;
        }
    }

    public function getPages($treeItems)
    {
         if ($treeItems->count() > 0) {
            foreach ($treeItems as $row) {
                $arrIds[] = $row->id;
            }
            return $this->find($arrIds);
        } else {
            return null;
        }
    }

    /**
     * this function returns the parent of the selected page
     * you can pass it a page id (integer) or a page object
     *
     * @param  mixed $page
     * @return Zend_Db_Table_Rowset
     */
    public function getParent($page)
    {
        $id = $this->_getPageId($page);
        $result = $this->find($id)->current();
        return $this->find($result->parent_id)->current();
    }

    /**
     * this function returns an array of the parents of the current page
     *
     * @param  mixed $page
     * @return unknown
     */
    public function getParents($page)
    {
        $parents = null;
        while ($parent = $this->getParent($page)) {
            $parents[$parent->id] = $parent;
            $page = $parent;
        }
        if (is_array($parents)) {
            return $parents;
        }
    }

    /**
     * this function tests whether the page is a child of another page
     *
     * @param  mixed $page
     * @param  mixed $parent
     * @return boolean
     */
    public function isChildOf($page, $parent)
    {
        $pageId = $this->_getPageId($page);
        $parentId = $this->_getPageId($parent);

        $where[] = $this->_db->quoteInto('id = ?', $pageId);
        $where[] = $this->_db->quoteInto('parent_id = ?', $parentId);

        if ($this->fetchRow($where)) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * this function tests whether the page is a parent of the other page
     *
     * @param  mixed $page
     * @param  mixed $parent
     * @return boolean
     */
    public function isParentOf($page, $child)
    {
        $pageId = $this->_getPageId($page);
        $childId = $this->_getPageId($child);

        $where[] = $this->_db->quoteInto('id = ?', $childId);
        $where[] = $this->_db->quoteInto('parent_id = ?', $pageId);

        if ($this->fetchRow($where)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * this function tests whether the page has children
     *
     * @param  mixed $page
     * @return boolean
     */
    public function hasChildren($page)
    {
        $pageId = $this->_getPageId($page);

        $where[] = $this->_db->quoteInto('parent_id = ?', $pageId);

        if ($this->fetchRow($where)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * this function returns the siblings for a selected page
     * you can pass it a page id (integer) or a page object
     * you can optionally pass it an array of where clauses
     *
     * @param  mixed $page
     * @param  array $where
     * @return Zend_Db_Table_Rowset
     */
    public function getSiblings($page, $where = array())
    {
        $id = $this->_getPageId($page);
        $parent = $this->getParent($page);

        //do not return the current page
        $where[] = $this->_db->quoteInto('id != ?', $id);

        return $this->getChildren($parent, $where);
    }

    /**
     * returns the current site index
     *
     * @return array
     */
    public function getIndex($rootId = 0, $order = null)
    {
        if (empty($this->_index)) {
            $this->_indexPages($rootId, null, '/', $order);
        }
        return $this->_index;
    }

    /**
     * creates loads the page index
     * if you pass the optional parentId the index will start with this page
     * if not it will index the whole site
     *
     * @param int $parentId
     */
    private function _indexPages($parentId = 0, $path = null, $pathSeparator = '/', $order = null)
    {
        if ($this->hasChildren($parentId)) {
            $children = $this->getChildren($parentId, null, $order);
            foreach ($children as $child) {
                //check to see if the child has children
                $tmpPath = $path . $child->name;

                //add the child
                $this->_index[$child->id] = $tmpPath;

                $this->_indexPages($child->id, $tmpPath . $pathSeparator, $pathSeparator);
            }
        }
    }

    /**
     * moves a page from one parent to another
     *
     * @param mixed $page
     * @param mixed $parent
     */
    public function movePage($page, $parent)
    {
        $this->_flushCache();
        $id = $this->_getPageId($page);
        $parentId = $this->_getPageId($parent);
        $row = $this->find($id)->current();
        if ($row) {
            $row->parent_id = $parentId;
            $row->save();
        }
    }

    /**
     * this function removes all of the children from a page
     *
     * @param mixed $page
     */
    public function removeChildren($page)
    {
        $this->_flushCache();
        $children = $this->getChildren($page);
        if ($children) {
            foreach ($children as $child) {
                $this->removeChildren($child, true);
            }
           $where = array();
           $where[] = $this->_db->quoteInto('parent_id = ?', $this->_getPageId($page));
           $this->delete($where);
        }
    }

    /**
     * removes the selected page and all of its children
     *
     * @param mixed $page
     */
    public function removePage($page)
    {
        $id = $this->_getPageId($page);
        $where[] = $this->_db->quoteInto('id = ?', $id);
        $this->delete($where);
        $this->removeChildren($page);
    }

    public function makeHomePage($pageId)
    {
        $data['is_home_page'] = 0;
        $this->update($data);

        unset($data);
        $data['is_home_page'] = 1;
        $where[] = $this->_db->quoteInto('id = ?', $pageId);
        $this->update($data, $where);
    }

    public function select($withFromPart = self::SELECT_WITHOUT_FROM_PART)
    {
        $select = parent::select($withFromPart);
        $select->where('namespace = ?', $this->_namespace);
        return $select;
    }

    static function isHomePage($page)
    {
        if (is_object($page) && $page->page->is_home_page == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function getHomePage()
    {
        $settings = new Model_SiteSettings();
        $homePage = $settings->get('home_page');

        //the home page defaults to the first page added to the CMS
        if ($homePage > 0) {
            return $homePage;
        } else {
            $select = $this->select();
            $select->order('id');
            $defaultHomePage = $this->fetchRow($select);
            return $defaultHomePage->id;
        }
    }

    public function get404Page()
    {
        $settings = new Model_SiteSettings();
        $page = $settings->get('page_not_found');

        $front = Zend_Controller_Front::getInstance();
        if ($page > 0) {
            $response = $front->getResponse();
            $response->setRawHeader('HTTP/1.1 404 Not Found');
            return $page;
        }
    }

    public function getOfflinePage()
    {
        $settings = new Model_SiteSettings();
        return $settings->get('offline_page');
    }

    /**
     * if page is an object then this returns its id property
     * otherwise it returns its integer value
     *
     * @param  mixed $page
     * @return unknown
     */
    private function _getPageId($page)
    {
        if (is_object($page)) {
            return $page->id;
        } else {
            return intval($page);
        }
    }

    /**
     * returns the next position of the children of a page
     *
     * @param int $parentId
     * @return int
     */
    private function _getNextPosition($parentId)
    {
        $last = $this->_getLastPosition($parentId);
        $next = intval($last) + 1;
        return $next;
    }

    /**
     * returns the last (highest) position of the children of a page
     *
     * @param  int $parentId
     * @return int
     */
    private function _getLastPosition($parentId)
    {
        $where[] = $this->_db->quoteInto('parent_id = ?', $parentId);
        $order = 'position DESC';
        $row = $this->fetchRow($where, $order);
        return $row->position;
    }

    private function _flushCache()
    {
        $cache = Zend_Registry::get('cache');
        $cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('filetree'));
        $cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('tree'));
    }

    private function _fetchPointer($uri, $parent = 0)
    {
        if (is_array($uri)) {
            foreach ($uri as $uriPart) {
                //fetch the pointer to the current uri part
                $pointer = $this->_getPageByLabel($uriPart, $parent);

                //if the page was not found then return null
                if (null == $pointer) {
                    return null;
                }

                //set the parent id to the current pointer to traverse down the tree
                $parent = $pointer;
            }

            return $pointer;
        } else {
            return $this->getHomePage();
        }
    }

    private function _getPageByLabel($label, $parent = 0)
    {
        if ($label != 'p') {
            $where[] = $this->_db->quoteInto('(label = ? OR name = ?)', $label);
            $where[] = $this->_db->quoteInto('parent_id = ?', $parent);
            $page = $this->fetchRow($where);
            if ($page) {
                return $page->id;
            } else {
                return null;
            }
        }
    }

    /**
     * Publish page action
     *
     * publish_level can either be:
     *  self::PUBLISH_ID   - published
     *  self::UNPUBLISH_ID - unpublished
     *  self__ARCHIVE_ID   - archived
     *
     * @param  int    $pageId  Id of the current page
     * @param  string  $action  action to perform, either 'archive', 'unpublish' or 'publish'
     * @return boolean
     */
    public function publishPage($pageId, $action = null)
    {
        $page = $this->find($pageId)->current();
        if ($page) {
            switch (trim($action)) {
                case 'archived':
                    $data['archive_date']  = time();
                    $data['publish_level'] = self::ARCHIVE_ID;
                    break;
                case 'unpublished':
#                    $data['publish_date']  = 'NULL';
                    $data['publish_level'] = self::UNPUBLISH_ID;
                    break;
                case 'published':
                default:
                    $data['publish_date']  = time();
                    $data['publish_level'] = self::PUBLISH_ID;
                    break;
            }
            $where[] = $this->_db->quoteInto('id = ?', $pageId);
            $this->update($data, $where);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get publishing dates
     *
     * Get publishing dates of a given page
     *
     * @param  int  $pageId  Id of the page to retrieve the publishing dates
     * @return array         An array of the publishing dates or null
     */
    public function getPublishDates($pageId)
    {
        $currentPage = $this->find($pageId)->current();
        if ($currentPage) {
            return array(
                'create_date'   => $currentPage->create_date,
                'publish_date'  => $currentPage->publish_date,
                'archive_date'  => $currentPage->archive_date,
                'publish_level' => $currentPage->publish_level,
            );
        }
        return null;
    }

    /**
     * IsPublished action
     *
     * Check whether page is published
     *
     * @param  int  $pageId  Id of the page to check
     * @return boolean       Returns true if the page is published
     */
    public function isPublished($pageId)
    {
        $currentPage = $this->find($pageId)->current();
        if ($currentPage && self::PUBLISH_ID === (int)$currentPage->publish_level) {
            return true;
        }
        return false;
    }

    /**
     * Get publishing Level
     *
     * Retrieve the publishing level for a given page id
     *
     * @param  int  $pageId  Id of the page to retrieve the publishing level
     * @return int|boolean   Returns the publishing level or false if the page doesn't exist
     */
    public function getPublishLevel($pageId)
    {
        $currentPage = $this->find($pageId)->current();
        if ($currentPage) {
            $publishStatus = $this->getStatusTemplates();
            return $publishStatus[$currentPage->publish_level];
        }
        return false;
    }

    /**
     * Get pages by publishing state
     *
     * Retrieve all pages that have not been published yet
     *
     * @param  string  $level  Publishing state to retrieve pages for
     * @return Zend_Db_Table_Rowset|null  An object containing pages with given publishing state
     */
    public function getPagesByPublishState($level = null, $order = null, $limit = null, $offset = null)
    {
        if ($level == null) {
            $level = self::UNPUBLISH_ID;
        }

        if ($order == null) {
            $order = 'id ASC';
        }

        $where[] = $this->_db->quoteInto('publish_level = ?', (int)$level);

        $result = $this->fetchAll($where, $order, $limit, $offset);
        if ($result->count() > 0) {
            $pageIds = array();
            foreach ($result as $page) {
                $pageIds[] = $page->id;
            }
            return $pageIds;
        } else {
            return null;
        }
    }

    /**
     * Returns the status templates
     *
     * @return array
     */
    public function getStatusTemplates()
    {
        return $this->_statusTemplates;
    }

}