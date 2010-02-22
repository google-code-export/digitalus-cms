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
 * @category   Digitalus CMS
 * @package   Digitalus_Core_Library
 * @copyright  Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: RegisterHelpers.php Tue Dec 25 21:38:04 EST 2007 21:38:04 forrest lyman $
 */

class Digitalus_View_RegisterHelpers
{

    /**
     * indexes the helper library and adds the script paths to the subdirs
     */
    public static function register($view)
    {
        $helperDirs = Digitalus_Filesystem_Dir::getDirectories('./application/helpers');
        if (is_array($helperDirs)) {
            foreach ($helperDirs as $dir) {
                $view->addHelperPath('./application/helpers/' . $dir, 'Digitalus_View_Helper_' . ucfirst($dir));
            }
        }
    }

}