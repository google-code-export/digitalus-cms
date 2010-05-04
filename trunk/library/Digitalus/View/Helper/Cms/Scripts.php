<?php
/**
 * Scripts helper
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
 * @version     $Id$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * Scripts helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */
class Digitalus_View_Helper_Cms_Scripts extends Zend_View_Helper_Abstract
{
    /**
     * inserts the code to include a script
     * pretty simple stuff, but nice and clean in the view
     *
     * @param files, array
     */
    public function scripts($files)
    {
        //get the style path
        $config = Zend_Registry::get('config');

        //build xhtml
        $xhtml = PHP_EOL . '<!--Beginning of scripts-->' . PHP_EOL;
        foreach ($files as $file) {
            $path = '/' . $config->filepath->script . '/' . $file;
            $xhtml .= "\t" . '<script type="text/javascript" src="' . $path . '"></script>' . PHP_EOL;
        }
        $xhtml .= '<!--End of scripts-->' . PHP_EOL;
        return $xhtml;
    }
}