<?php
/**
 * LoadEditors helper
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
 * @category    Digitalus
 * @package     Digitalus_View
 * @subpackage  Helper
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id:$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.9.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * LoadEditors helper
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.9.0
 */
class Digitalus_View_Helper_Content_LoadEditors extends Zend_View_Helper_Abstract
{
    protected $_allowedEditors = array('ckeditor', 'fckeditor', 'markitup', 'tinymce', 'wymeditor', 'wysiwyg');

    protected $_editorTypes = array();

    public function loadEditors($controls)
    {
        $adminLanguage = $this->view->getAdminLanguage();

        $this->_editorTypes = $this->_getEditorTypes($controls);

        foreach ($this->_allowedEditors as $editor) {
            if (in_array($editor, $this->_editorTypes)) {
                switch (strtolower($editor)) {
                    case 'ckeditor':
                        // integrates CkEditor
                        $this->view->jQuery()->addJavascriptFile($this->view->getBaseUrl() . '/scripts/ckeditor/ckeditor.js');
                        $this->view->jQuery()->onLoadCaptureStart();?>
                            $('.ckeditor').ckeditor({
                                language : '<?php echo $adminLanguage;?>',
                            });<?php
                        $this->view->jQuery()->onLoadCaptureEnd();
                        break;
                    case 'fckeditor':
                    default:
                        // integrates FckEditor
                        $this->view->jQuery()->addJavascriptFile($this->view->getBaseUrl() . '/scripts/fckeditor/jquery.FCKEditor.pack.js');
                        $this->view->jQuery()->onLoadCaptureStart();?>
                            $('.fckeditor').fck({
                                path: '<?php echo $this->view->getBaseUrl() . '/scripts/fckeditor/';?>',
                                toolbar: 'Digitalus',
                                height: 300
                            });<?php
                        $this->view->jQuery()->onLoadCaptureEnd();
                        break;
                    case 'markitup':
                        // integrates MarkItUp
                        $this->view->jQuery()->addStylesheet($this->view->getBaseUrl() . '/scripts/markitup/skins/markitup/style.css');
                        $this->view->jQuery()->addStylesheet($this->view->getBaseUrl() . '/scripts/markitup/sets/html/style.css');
                        $this->view->jQuery()->addJavascriptFile($this->view->getBaseUrl() . '/scripts/markitup/jquery.markitup.js');
                        $this->view->jQuery()->addJavascriptFile($this->view->getBaseUrl() . '/scripts/markitup/sets/html/set.js');
                        $this->view->jQuery()->onLoadCaptureStart();?>
                            $('.markItUp').markItUp(
                                mySettings
                            );<?php
                        $this->view->jQuery()->onLoadCaptureEnd();
                        break;
                    case 'tinymce':
                        // integrates TinyMce
                        $this->view->jQuery()->addJavascriptFile($this->view->getBaseUrl() . '/scripts/tinymce/jquery.tinymce.js');
                        $this->view->jQuery()->onLoadCaptureStart();?>
                            jQuery('.tinymce').tinymce({
                                lang: '<?php echo $adminLanguage;?>',
                                theme: 'advanced',
                                script_url: '<?php echo $this->view->getBaseUrl() . '/scripts/tinymce/tiny_mce.js';?>',
                            });<?php
                        $this->view->jQuery()->onLoadCaptureEnd();
                        break;
                    case 'wymeditor':
                        // integrates WymEditor
                        $this->view->jQuery()->addJavascriptFile($this->view->getBaseUrl() . '/scripts/wymeditor/jquery.wymeditor.min.js');
                        $this->view->jQuery()->onLoadCaptureStart();?>
                            jQuery('.wymeditor').wymeditor({
                                lang: '<?php echo $adminLanguage;?>',
                                skin: 'compact',
                                stylesheet: 'styles.css',
                                postInit: function(wym) {
                                    wym.fullscreen();
                                    wym.hovertools();
                                    wym.resizable();
                                    wym.tidy();
                                }
                            });<?php
                        $this->view->jQuery()->onLoadCaptureEnd();
                        break;
                    case 'wysiwyg':
                        // integrates Jwysiwyg
                        $this->view->jQuery()->addStylesheet($this->view->getBaseUrl() . '/scripts/jquery/plugins/wysiwyg/jquery.wysiwyg.css');
                        $this->view->jQuery()->addJavascriptFile($this->view->getBaseUrl() . '/scripts/jquery/plugins/wysiwyg/jquery.wysiwyg.js');
                        $this->view->jQuery()->onLoadCaptureStart();?>
                            $('.wysiwyg').wysiwyg({
                                controls: {
                                    separator04: {visible: true},
                                    insertOrderedList: {visible: true},
                                    insertUnorderedList: {visible: true},
                                }
                            });<?php
                        $this->view->jQuery()->onLoadCaptureEnd();
                    break;
                }
            }
        }
    }

    protected function _getEditorTypes($controls)
    {
        foreach ($controls as $control) {
            $this->_editorTypes[] = $control->getAttrib('type');
        }
        array_unique($this->_editorTypes);

        return $this->_editorTypes;
    }

}