<?php
/**
 * SelectSkin helper
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
 * @version     $Id: SelectSkin.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * SelectSkin helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper Digitalus_View_Helper_GetTranslation
 */
class Digitalus_View_Helper_Admin_SelectSkin extends Zend_View_Helper_Abstract
{
    /**
     *
     */
    public function selectSkin($name, $value = null, $attr = null, $defaut = null)
    {
        $config = Zend_Registry::get('config');
        $pathToPublicSkins = $config->design->pathToSkins;
        $skins = Digitalus_Filesystem_Dir::getDirectories($pathToPublicSkins);
        if ($defaut == NULL) {
            $defaut = $this->view->getTranslation('Select One');
        }
        $options[0] = $defaut;

        if (is_array($skins)) {
            foreach ($skins as $skin) {
                $options[$skin] = $skin;
            }
            return $this->view->formSelect($name, $value, $attr, $options);
        } else {
            return null;
        }
    }
}