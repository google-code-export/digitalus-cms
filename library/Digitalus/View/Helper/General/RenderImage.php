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
    public function renderImage($src, $height = '', $width = '', $attribs = array())
    {
        $absPath = BASE_PATH . '/' . $src;
        if ($src != '' && is_file($absPath)) {
            $imageSize = getimagesize($absPath);
            $srcHeight = $imageSize[0];
            $srcWidth = $imageSize[1];

            $percentage = 1.0;
            //if the height is greater than the width then adjust by the height
            //otherwise adjust by the width
            if ((isset($height) && !empty($height)) && $srcHeight > $srcWidth) {
                $percentage = $height / $srcHeight;
            } elseif ((isset($width) && !empty($width))) {
                $percentage = $width / $srcWidth;
            }

            if (isset($height) && !empty($height)) {
                $height = 'height:' . round($srcHeight * $percentage) . 'px; ';
            } else {
                $height = '';
            }
            if (isset($width) && !empty($width)) {
                //gets the new value and applies the percentage, then rounds the value
                $width = 'width:' . round($srcWidth * $percentage) . 'px; ';
            } else {
                $width = '';
            }

            $attributes = null;
            if (is_array($attribs)) {
                foreach ($attribs as $k => $v) {
                    $attributes .= $k . "='" . $v . "' ";
                }
            }
            return '<img class="icon" style="' . $width . $height . '" src="' . $this->view->getBaseUrl() . '/' . $src . '" ' . $attributes . ' />';
        }
    }
}