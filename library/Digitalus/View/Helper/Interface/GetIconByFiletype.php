<?php
/**
 * GetIconByFileType helper
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
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_View
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: GetIconByFileType.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * GetIconByFileType helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper Digitalus_View_Helper_GetBaseUrl
 */
class Digitalus_View_Helper_Interface_GetIconByFileType extends Zend_View_Helper_Abstract
{
    public $defaultIcon = 'page.png';
    public $folderIcon = 'folder.png';
    public $icons = array();

    /**
     *
     */
    public function getIconByFileType($file, $asImage = true)
    {
        $config = Zend_Registry::get('config');
        $this->icons = $config->filetypes;
        $icon = $this->getIcon($file);
        if ($asImage) {
            $base = $this->view->getBaseUrl() . '/' . $config->filepath->icons;
            return '<img src="' . $base . '/' . $icon . '" />';
        } else {
            return $icon;
        }
    }

    public function getIcon($file)
    {
        $filetype = Digitalus_Media_Filetype::load($file);
        if ($filetype != null) {
            $type = $filetype->key;

            if (isset($this->icons->$type)) {
                $filetype = $this->icons->$type;
                return $filetype->icon;
            }
        }
        return $this->defaultIcon;
    }
}