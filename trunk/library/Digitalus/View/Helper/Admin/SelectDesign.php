<?php
/**
 * SelectDesign helper
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
 * @author      Forrest Lyman
 * @category    Digitalus
 * @package     Digitalus_View
 * @subpackage  Helper
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id:$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * SelectDesign helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */
class Digitalus_View_Helper_Admin_SelectDesign extends Zend_View_Helper_Abstract
{
    /**
     *
     */
    public function selectDesign($name, $value = null, $attr = null)
    {
        $templateConfig = Zend_Registry::get('config')->template;
        $templates = Digitalus_Filesystem_Dir::getDirectories(BASE_PATH . '/' . $templateConfig->pathToTemplates . '/public');
        foreach ($templates as $template) {
            $designs = Digitalus_Filesystem_File::getFilesByType(BASE_PATH . '/' . $templateConfig->pathToTemplates . '/public/' . $template . '/designs', 'xml');
            if (is_array($designs)) {
                foreach ($designs as $design) {
                    $design = Digitalus_Toolbox_Regex::stripFileExtension($design);
                    $options[$template . '_' . $design] = $template . ' / ' . $design;
                }
            }
        }
        return $this->view->formSelect($name, $value, $attr, $options);
    }
}