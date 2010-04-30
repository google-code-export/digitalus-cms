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
 * @since       Release 1.10.0
 */

/**
 * @see Digitalus_Form
 */
require_once 'Digitalus/Form.php';

/**
 * Admin Site Form
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Admin
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10.0
 * @uses        Model_Page
 */
class Admin_Form_Media extends Digitalus_Form
{
    protected $_data = array();

    /**
     * Constructor
     *
     * Registers form view helper as decorator
     *
     * @param mixed $options
     * @param array $data
     * @return void
     */
    public function __construct($options = null, $data)
    {
        // set this form's specific data
        $this->_setData($data);

        // run constructor (which runs the init() method)
        parent::__construct($options);
    }

    /**
     * Initialize the form
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        $view = $this->getView();

        $pathToMedia     = $this->_getData('pathToMedia');
        $mediaFolder     = $this->_getData('mediaFolder');
        $path            = $this->_getData('path');
        $filepath        = $this->_getData('filepath');
        $mediapath       = $this->_getData('mediapath');
        $files           = $this->_getData('files');
        $folders         = $this->_getData('folders');
        $folderPathParts = $this->_getData('folderPathParts');
        $label           = $this->_getData('label');

        /* *********************************************************************
         * CURRENT DIRECTORY
         * ****************************************************************** */
        $pathParts[] = $pathToMedia;
        if (is_array($folderPathParts)) {
            foreach ($folderPathParts as $path2 => $label2) {
                $pathParts[] = '<a href="' . $view->getBaseUrl() . '/admin/media/open-folder/folder/' . $path2 . '">'
                             . Digitalus_Toolbox_String::stripUnderscores($label2)
                             . '</a>';
            }
        }
        $xhtml = '<p>' . $view->getTranslation('Media Root') . implode('/', $pathParts) . '</p>';

        $currentDirectory = $this->createElement('AnyMarkup', 'current_directory', array(
            'value'         => $xhtml,
            'decorators'    => $this->getStandardDecorator('none'),
        ));

        /* *********************************************************************
         * BROWSE
         * ****************************************************************** */
        $siteRoot = $view->getBaseUrl() . '/';
        $basePath = $siteRoot . $mediaFolder;
        if (!empty($filepath)) {
            $basePath .= '/' . $filepath;
        }

        $xhtml = '';
        // DIRECTORIES
        if (is_array($folders) && count($folders) > 0) {
            $xhtml = '<h3>' . $view->getTranslation('Subfolders') . '</h3>';
            foreach ($folders as $folder) {
                $folder = Digitalus_Toolbox_String::addUnderscores($folder);

                $cleanPath  = Digitalus_Toolbox_String::stripHyphens($folder);
                $cleanPath  = Digitalus_Toolbox_String::stripUnderscores($folder);
                $deleteLink = '/admin/media/delete-folder/folder/' . $mediapath . '_' . $folder;
                $path2      = '/admin/media/open-folder/folder/'   . $mediapath . '_' . $folder;
                $xhtml     .= '<div class="folderWrapper">'
                           .  '    '     . $view->link('Delete', $deleteLink, 'delete.png', 'rightLink delete')
                           .  '    <h4>' . $view->link($cleanPath, $path2, 'folder.png') . '</h4>'
                           .  '    <p>'  . $view->getTranslation('Full path') . ': <code>' . $basePath . '/' . $cleanPath . '</code></p>'
                           .  '</div>';
            }
        }

        // FILES
        if (is_array($files) && count($files) > 0) {
            $xhtml .= '<h3>' . $view->getTranslation('Files') . '</h3>';
            foreach ($files as $file) {
                if (substr($file, 0, 1) != '.') {
                    $filepath2  = Digitalus_Toolbox_String::stripUnderscores($basePath) . '/' . $file;
                    $fileLink   = $mediapath . '_' . $file;
                    $deleteLink = '/admin/media/delete-file/file/' . $fileLink . '/';
                    $path2      = $mediaFolder . '/' . $filepath . $filepath2;
                    $xhtml   .= '<div class="fileWrapper">'
                             .  '    '     . $view->link('Delete', $deleteLink, 'delete.png', 'rightLink delete')
                             .  '    <h4>' . $view->link($file, $path2, $view->getIconByFiletype($filepath2, false)) . '</h4>'
                             .  '    <p>'  . $view->getTranslation('Full path') . ': <code>' . $basePath . $filepath2 . '</code></p>'
                             .  '</div>';
                }
            }
        }

        $subDirectories = $this->createElement('AnyMarkup', 'sub_directories', array(
            'value'         => $xhtml,
            'decorators'    => $this->getStandardDecorator('none'),
        ));

        /* *********************************************************************
         * UPLOAD
         * ****************************************************************** */
        $filePath = $this->createElement('hidden', 'filepath', array(
            'value'         => $filepath,
            'decorators'    => $this->getStandardDecorator('none'),
        ));
        $mediapath = $this->createElement('hidden', 'mediapath', array(
            'value'         => $mediapath,
            'decorators'    => $this->getStandardDecorator('none'),
        ));

        $uploads = $this->createElement('file', 'uploads[]', array(
            'label'         => $view->getTranslation('Select the files to upload'),
            'belongsTo'     => 'uploads',
            'attribs'       => array('id' => 'multi_upload'),
            'filters'       => array('StringTrim', 'StripTags'),
            'validators'    => array(
                array('File_NotExists', true, array()),
            ),
        ));

        $uploadSubmit = $this->createElement('submit', 'uploadSubmit', array(
            'label'         => $view->getTranslation('Upload Files'),
            'attribs'       => array('class' => 'submit'),
        ));

        /* *********************************************************************
         * CREATE SUBFOLDER
         * ****************************************************************** */
        $path = $this->createElement('hidden', 'path', array(
            'value'         => $path,
            'decorators'    => $this->getStandardDecorator('none'),
        ));
        $folderName = $this->createElement('text', 'folder_name', array(
            'required'      => true,
            'label'         => $view->getTranslation('Folder Name'),
            'filters'       => array('StringTrim', 'StripTags'),
            'validators'    => array(
                array('NotEmpty', true),
                array('Regex', true, array(
                    'pattern'  => Digitalus_Media::MEDIALABEL_REGEX,
                    'messages' => array('regexNotMatch' => Digitalus_Media::MEDIALABEL_REGEX_NOTMATCH),
                )),
            ),
        ));
        $createFolderSubmit = $this->createElement('submit', 'createFolderSubmit', array(
            'label'         => $view->getTranslation('Create Folder'),
            'attribs'       => array('class' => 'submit'),
        ));

        /* *********************************************************************
         * RENAME FOLDER
         * ****************************************************************** */
        $newName = $this->createElement('text', 'new_folder_name', array(
            'required'      => true,
            'label'         => $view->getTranslation('New Name'),
            'value'         => $label,
            'filters'       => array('StringTrim', 'StripTags'),
            'validators'    => array(
                array('NotEmpty', true),
                array('Regex', true, array(
                    'pattern'  => Digitalus_Media::MEDIALABEL_REGEX,
                    'messages' => array('regexNotMatch' => Digitalus_Media::MEDIALABEL_REGEX_NOTMATCH),
                )),
            ),
        ));
        $renameFolderSubmit = $this->createElement('submit', 'renameFolderSubmit', array(
            'label'         => $view->getTranslation('Rename Folder'),
            'attribs'       => array('class' => 'submit'),
        ));

        /* *********************************************************************
         * PUT IT ALL TOGETHER
         * ****************************************************************** */
        $this->addElement($currentDirectory);
        $this->addElement($subDirectories);
        $this->addElement($filePath);
        $this->addElement($mediapath);
        $this->addElement($uploads);
        $this->addElement($path);
        $this->addElement($folderName);
        $this->addElement($newName);
        $this->addElement($uploadSubmit);
        $this->addElement($createFolderSubmit);
        $this->addElement($renameFolderSubmit);

        $this->addDisplayGroup(
            array('form_instance', 'filepath', 'mediapath', 'current_directory'),
            'mediaCurrentGroup',
            array('legend' => $view->getTranslation('Current Folder'))
        );
        $this->addDisplayGroup(
            array('sub_directories'),
            'mediaSubDirectoriesGroup',
            array('legend' => $view->getTranslation('Current folder contents'))
        );
        $this->addDisplayGroup(
            array('uploads', 'uploadSubmit'),
            'mediaUploadGroup',
            array('legend' => $view->getTranslation('Upload Files'))
        );
        $this->addDisplayGroup(
            array('path', 'folder_name', 'createFolderSubmit'),
            'mediaCreateFolderGroup',
            array('legend' => $view->getTranslation('Create Subfolder'))
        );
        $this->addDisplayGroup(
            array('new_folder_name', 'renameFolderSubmit'),
            'mediaRenameFolderGroup',
            array('legend' => $view->getTranslation('Rename Folder'))
        );
   }

    protected function _setData($data)
    {
        $this->_data = $data;
    }

    protected function _getData($key)
    {
        if (isset($this->_data[$key]) && !empty($this->_data[$key])) {
            return $this->_data[$key];
        }
        return null;
    }
}