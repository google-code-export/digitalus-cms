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
 * @author      Forresst Lyman
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_Menu
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id$
 */

require_once 'Zend/Navigation/Page/Uri.php';

class Digitalus_Menu_Item extends Zend_Navigation_Page_Uri
{
    public $view;
    protected $_item;
    public $id;
    public $hasSubmenu = false;

    /**
     * Constructor
     *
     * @param array|Zend_Config $options An array or config object with Zend_Navigation_Page options
     * @param object $item A Zend_Db_Table_Row object
     */
    public function __construct($options = null, Zend_Db_Table_Row $item)
    {
        $this->setView();
        $this->_item = $item;
        $this->id    = $this->_item->id;
        $pageOptions          = $this->_getPageAsArray();
        $pageOptions['pages'] = $this->_getChildrenAsArray();
        $this->setOptions($pageOptions);
        $this->_setActive();
        $this->_setVisible();

        parent::__construct($options);
    }

    protected function _setActive($item = null)
    {
        if (empty($item)) {
            $item = $this->getItem();
        }

        $uri       = new Digitalus_Uri();
        $uriString = strtolower($uri->toString());
        if (strtolower(Digitalus_Toolbox_Page::getCurrentPageName()) == strtolower($item->name) ||
            (empty($uriString) && strtolower(Digitalus_Toolbox_Page::getHomePageName($item)) == strtolower(Digitalus_Toolbox_Page::getUrl($item)))
        ) {
            $active = true;
        } else {
            $active = false;
        }
        $this->setActive($active);
    }

    protected function _setVisible($item = null)
    {
        if (empty($item)) {
            $item = $this->getItem();
        }
        if (1 == $item->publish_level && $item->show_on_menu) {
            $visible = true;
        } else {
            $visible = false;
        }
        $this->setVisible($visible);
    }

    /**
     * Check whether current item has children
     *
     * @return  bool  Returns true if the current item has children, otherwise false
     */
    protected function _hasChildren()
    {
        $mdlMenu = new Model_Menu();
        if ($mdlMenu->hasChildren($this->id)) {
            return true;
        }
        return false;
    }

    /**
     * Get Page data as array
     *
     * @return  array  Returns an array of the page data, otherwise an empty array
     */
    protected function _getPageAsArray($item = null)
    {
        if (empty($item)) {
            $item = $this->getItem();
        }
        $this->setView();
        $baseUrl = $this->view->baseUrl();
        $page = array(
            'active'    => $this->isActive(false),
            'class'     => 'menuItem',
            'id'        => $item->id,
            'label'     => Digitalus_Toolbox_Page::getLabel($item),
            'name'      => $item->name,
            'resource'  => strtolower(Digitalus_Toolbox_String::replaceEmptySpace($item->name)),
            'title'     => Digitalus_Toolbox_Page::getLabel($item),
            'uri'       => $baseUrl . '/' . Digitalus_Toolbox_String::replaceEmptySpace(Digitalus_Toolbox_Page::getUrl($item)),
            'visible'   => $this->isVisible($item),
        );
        return $page;
    }

    /**
     * Add children if they exist
     *
     * @return  array  Returns an array of all children, otherwise an empty array
     */
    protected function _getChildrenAsArray()
    {
        $subPages = array();
        if ($this->_hasChildren()) {
            $mdlMenu  = new Model_Menu();
            $children = $mdlMenu->getChildren($this->id);
            foreach ($children as $child) {
                $page = new Digitalus_Menu_Item(null, $child);
                $pageOptions          = $page->_getPageAsArray();
                $pageOptions['pages'] = $page->_getChildrenAsArray();
                $page->setOptions($pageOptions);
                $subPages[] = $page;
            }
        }
        return $subPages;
    }

    /**
     * Retrieve the inner item
     *
     * @return  Zend_Db_Table_Row   Returns a Zend_Db_Table_Row object
     */
    public function getItem()
    {
        return $this->_item;
    }

    public function getView()
    {
        return $this->view;
    }

    public function setView(Zend_View $view = null)
    {
        if ($view == null) {
            $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
            if (null === $viewRenderer->view) {
                $viewRenderer->initView();
            }
            $this->view = $viewRenderer->view;
        } else {
            $this->view = $view;
        }
    }
}