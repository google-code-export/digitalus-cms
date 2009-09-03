<?php
/**
 * RenderImage helper
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
 * RenderImage helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */
class Digitalus_View_Helper_General_RenderImage extends Zend_View_Helper_Abstract
{
    /**
     * comments
     */
    public function renderImage($src, $height, $width,$attribs = false)
    {
        $absPath = BASE_PATH . $src;
        if ($src != '' && is_file($absPath)) {
            $imageSize = getimagesize($absPath);
            $srcHeight = $imageSize[0];
            $srcWidth = $imageSize[1];

            //if the height is greater than the width then adjust by the height
            //otherwise adjust by the width
            if ($srcHeight > $srcWidth) {
                $percentage = $height / $srcHeight;
            } else {
                $percentage = $width / $srcWidth;
            }

            //gets the new value and applies the percentage, then rounds the value
            $width = round($srcWidth * $percentage);
            $height = round($srcHeight * $percentage);

            $attributes = null;
            if ($attribs) {
                foreach ($attribs as $k => $v) {
                    $attributes .= $k . "='" . $v . "' ";
                }
            }
            return '<img width="' . $width . '" height="' . $height . '" src="' . $src . '" ' . $attributes . ' />';
        }
    }
}