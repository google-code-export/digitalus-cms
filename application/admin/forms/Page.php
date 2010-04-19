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
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: Page.php 701 2010-03-05 16:23:59Z lowtower@gmx.de $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.9.0
 */

/**
 * @see Digitalus_Form
 */
require_once 'Digitalus/Form.php';

/**
 * Admin Page Form
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Admin
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.9.0
 * @uses        Model_Page
 */
class Admin_Form_Page extends Digitalus_Form
{
    /**
     * Initialize the form
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        $view = $this->getView();

        // create new element
        $id = $this->createElement('hidden', 'id', array(
            'decorators'    => array('ViewHelper')
        ));
        $this->addElement($id);

        // create new element
        $name = $this->createElement('text', 'page_name', array(
            'label'         => $view->getTranslation('Page Name'),
            'required'      => true,
            'filters'       => array('StringTrim', 'StripTags'),
            'validators'    => array(
                array('NotEmpty', true),
                array('StringLength', true, array(4, Model_Page::PAGE_NAME_LENGTH)),
                array('PagenameExists'),
                array('Regex', true, array(
                    'pattern'  => Model_Page::PAGE_NAME_REGEX,
                    'messages' => array('regexNotMatch' => Model_Page::PAGE_NAME_REGEX_NOTMATCH),
                )),
            ),
            'attribs'       => array('size' => 50),
            'order'         => 0,
        ));
        $this->addElement($name);

        // add options for parent page
        $multiOptions = array(
            0 => $view->getTranslation('Site Root')
        );
        $mdlIndex = new Model_Page();
        $index = $mdlIndex->getIndex(0, 'name');
        if (is_array($index)) {
            foreach ($index as $id => $page) {
                $multiOptions[$id] =  $page;
            }
        }
        // create new element
        $parentId = $this->createElement('select', 'parent_id', array(
            'label'         => $view->getTranslation('Parent page') . ':',
            'required'      => true,
            'multiOptions'  => $multiOptions,
            'order'         => 1,
#            'errorMessages' => array('At least four and a maximum of twenty alphanumeric characters are allowed!'),
        ));
        $this->addElement($parentId);

        // add options for template
        $multiOptions = array();
        $templateConfig = Zend_Registry::get('config')->template;
        $templates = Digitalus_Filesystem_Dir::getDirectories(BASE_PATH . '/' . $templateConfig->pathToTemplates . '/public');
        foreach ($templates as $template) {
            $designs = Digitalus_Filesystem_File::getFilesByType(BASE_PATH . '/' . $templateConfig->pathToTemplates . '/public/' . $template . '/pages', 'xml');
            if (is_array($designs)) {
                foreach ($designs as $design) {
                    $design = Digitalus_Toolbox_Regex::stripFileExtension($design);
                    $multiOptions[$template . '_' . $design] = $view->getTranslation($template) . ' / ' . $view->getTranslation($design);
                }
            }
        }
        // create new element
        $contentTemplate = $this->createElement('select', 'content_template', array(
            'label'         => $view->getTranslation('Template') . ':',
            'required'      => true,
            'multiOptions'  => $multiOptions,
            'order'         => 2,
        ));
        $this->addElement($contentTemplate);

        // create new element
        $continue = $this->createElement('checkbox', 'continue_adding_pages', array(
            'label'         => $view->getTranslation('Continue adding pages') . '?',
            'order'         => 3,
        ));
        $this->addElement($continue);

        // create new element
        $showOnMenu = $this->createElement('checkbox', 'show_on_menu', array(
            'label'         => $view->getTranslation('Show Page on menu') . '?',
            'order'         => 4,
        ));
        $this->addElement($showOnMenu);

        // create new element
        $publish = $this->createElement('checkbox', 'publish_pages', array(
            'label'         => $view->getTranslation('Publish page instantly') . '?',
            'order'         => 5,
        ));
        $this->addElement($publish);

        // create new element
        $submit = $this->createElement('submit', 'submitPageForm', array(
            'label'         => $view->getTranslation('Submit'),
            'attribs'       => array('class' => 'submit'),
            'order'         => 1000,
        ));
        $this->addElement($submit);

        $this->addDisplayGroup(
            array('form_instance', 'id', 'page_name', 'parent_id', 'content_template', 'continue_adding_pages', 'show_on_menu', 'publish_pages', 'submitPageForm'),
            'createPageGroup');
    }
}