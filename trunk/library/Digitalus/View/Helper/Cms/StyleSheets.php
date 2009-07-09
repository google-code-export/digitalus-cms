<?php
/**
 * StyleSheets helper
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
 * StyleSheets helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */
class Digitalus_View_Helper_Cms_StyleSheets extends Zend_View_Helper_Abstract
{
    /**
     * inserts the code to include a style sheet
     * pretty simple stuff, but makes it easier than inserting your base url every time
     * if default is true it will insert the base url tothe default style directory
     *
     * @param files, array
     */
    public function StyleSheets($files, $default = true)
    {
        //get the style path
        $config = Zend_Registry::get('config');

        //build xhtml
        $xhtml = "\n<!--Begining of style sheets-->\n";
        foreach ($files as $file) {
            if ($default) {
                $path = '/' . $config->filepath->style . '/' . $file;
            } else {
                $path = $file;
            }
            $xhtml .= "\t<link rel='stylesheet' type='text/css' media='screen' href='{$path}' /> \n";
        }
        $xhtml .= "<!--End of style sheets-->\n";
        return $xhtml;
    }
}