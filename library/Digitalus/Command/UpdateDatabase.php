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

class Digitalus_Command_UpdateDatabase extends Digitalus_Command_Abstract
{

    /**
     * db adapter
     *
     * @var zend_db_table adapter
     */
    private $_db;

    /**
     * load the db adapter
     *
     */
    public function __construct()
    {
        $this->_db = Zend_Db_Table::getDefaultAdapter();
        parent::__construct();
    }

    /**
     *
     * create pages and content nodes tables
     * validate that there are pages in the content table.
     * migrate content table rows to pages / content_nodes
     *
     */
    public function run($params = null)
    {
        $result = $this->_createPages();
        if (!$result) {
            $this->log($this->view->getTranslation('ERROR: Could not create pages table.'));
        } else {
            $this->log($this->view->getTranslation('pages table created OK.'));

            $result = $this->_createContentModules();
            if (!$result) {
                $this->log($this->view->getTranslation('ERROR: Could not create content_nodes table.'));
            } else {
                $this->log($this->view->getTranslation('content_nodes table created OK.'));

                $result = $this->_transferData();
                if ($result == 0) {
                    $this->log($this->view->getTranslation('There are no content pages to migrate.'));
                } else {
                    $this->log($result . ' ' . $this->view->getTranslation('pages have been migrated successfully.'));
                }
            }
        }

    }

    /**
     * returns details about the current command
     *
     */
    public function info()
    {
        $this->log($this->view->getTranslation('The Update Database command will update your database from version 1.0.x to 1.5.x.'));
        $this->log($this->view->getTranslation('Params: none'));
    }

    private function _createPages()
    {
        $sql = "
            CREATE TABLE `pages` (
                `id` int(11) unsigned NOT null auto_increment,
                `content_type` varchar(50) default null,
                `format` varchar(50) default null,
                `reference` text,
                `related_content` text,
                `tags` text,
                `meta_keywords` text,
                `meta_description` text,
                `parent_id` int(10) unsigned NOT null default '0',
                `title` text NOT null,
                `show_on_menu` int(11) default '0',
                `label` varchar(100) default null,
                `position` int(2) default null,
                `headline` text NOT null,
                `template_path` varchar(100) default null,
                `layout_path` varchar(100) default null,
                `publish_level` int(10) unsigned NOT null default '0',
                `publish_date` int(11) default null,
                `archive_date` int(11) default null,
                `author_id` int(10) unsigned NOT null default '0',
                `create_date` int(10) unsigned NOT null default '0',
                `editor_id` int(10) unsigned NOT null default '0',
                `edit_date` int(10) unsigned NOT null default '0',
                `properties` text COMMENT 'this is linked to a list',
                `hits` int(11) default null,
                `filepath` varchar(250) default null,
                PRIMARY KEY  (`id`),
                KEY `REL_CONTENT` (`id`,`content_type`),
                FULLTEXT KEY `FT_CONTENT` (`tags`),
                FULLTEXT KEY `FT_RELATED_CONTENT` (`related_content`)
                ) ENGINE=MyISAM AUTO_INCREMENT=516 DEFAULT CHARSET=UTF8
            ";
        return $this->_db->query($sql);
    }

    private function _createContentModules()
    {
        $sql = "
            CREATE TABLE `content_nodes` (
                `id` int(11) NOT null auto_increment,
                `page_id` int(11) unsigned zerofill default null,
                `node` varchar(100) default null,
                `version` varchar(100) default null,
                `content_type` varchar(100) default null,
                `content` text,
                PRIMARY KEY  (`id`),
                KEY `NODE_TO_PAGE` (`page_id`),
                KEY `NODE_KEYS` (`node`)
                ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8
            ";
        return $this->_db->query($sql);
    }

    private function _transferData()
    {
        $totalPages = 0;

        $sql = "SELECT COUNT(id) as pages FROM content ";
        $pages = $this->_db->fetchRow($sql);
        if ($pages->pages > 0) {
            //load all of the content
            $sql = "SELECT * FROM content";
            $content = $this->_db->fetchAll($sql);
            //add each row
            foreach ($content as $row)  {
                $page = $this->_addContentPage($row);
                $nodes = $this->_addContentNodes($row);
                if ($nodes && $page) {
                    $totalPages++;
                }
            }
        }
        return $totalPages;
    }

    private function _addContentPage($row)
    {
        $data = array(
            'id'                => $row->id,
            'content_type'      => $row->content_type,
            'format'            => $row->format,
            'reference'         => $row->reference,
            'related_content'   => $row->related_content,
            'tags'              => $row->tags,
            'meta_keywords'     => $row->meta_keywords,
            'meta_description'  => $row->meta_description,
            'parent_id'         => $row->parent_id,
            'title'             => $row->title,
            'show_on_menu'      => $row->show_on_menu,
            'label'             => $row->label,
            'position'          => $row->position,
            'headline'          => $row->headline,
            'template_path'     => $row->template_path,
            'layout_path'       => $row->layout_path,
            'publish_level'     => $row->publish_level,
            'publish_date'      => $row->publish_date,
            'archive_date'      => $row->archive_date,
            'author_id'         => $row->author_id,
            'create_date'       => $row->create_date,
            'editor_id'         => $row->editor_id,
            'edit_date'         => $row->edit_date,
            'properties'        => $row->properties,
            'hits'              => $row->hits,
            'filepath'          => $row->filepath
            );

        return $this->_db->insert('pages', $data);
    }

    private function _addContentNodes($row)
    {
        $nodes = array('intro','content','additional_content');
        foreach ($nodes as $node)  {
            if (!empty($row->$node))  {
                $result = $this->_addContentNode($this->_db->lastInsertId(), $node, $row->$node);
                if (!$result)  {
                    $error = true;
                }
            }
        }
        if (!$error) {
            return true;
        }
    }

    private function _addContentNode($pageId, $node, $value)
    {
        $data = array(
            'page_id'       => $pageId,
            'node'          => $node,
            'content'         => $value
        );

        return $this->_db->insert('content_nodes', $data);
    }

}