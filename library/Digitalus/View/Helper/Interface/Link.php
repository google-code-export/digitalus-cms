<?php
/**
 * Link helper
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
 * @version     $Id: Link.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * Link helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper Digitalus_View_Helper_GetBaseUrl
 * @uses        viewHelper Digitalus_View_Helper_GetTranslation
 */
class Digitalus_View_Helper_Interface_Link extends Zend_View_Helper_Abstract
{
    public $link;
    public $baseUrl;

    /**
     *
     */
    public function link($label, $link, $icon = null, $class = 'link', $target = null, $suppressLabel = false, $translate = true)
    {
        $this->link = Digitalus_Toolbox_String::stripLeading('/', $link);
        $this->baseUrl = Digitalus_Toolbox_String::stripLeading('/', $this->view->getBaseUrl());

        // clean the link
        if ($this->isRemoteLink($link) || $this->isAnchorLink($link)) {
            $cleanLink = $link;
        } else {
            $cleanLink = '/' . $this->addBaseUrl($this->link);
        }

        if ($target != null) {
            $target = 'target="' . $target . '"';
        }

        $linkParts[] = '<a href="' . $cleanLink . '" class="' . $class .'" ' . $target . '>';

        if (null !== $icon) {
            $linkParts[] = $this->getIcon($icon, $label);
        }
        if (!empty($label) && true != $suppressLabel) {
            if (true === (bool)$translate) {
                $linkParts[] = $this->view->getTranslation((string)$label);
            } else {
                $linkParts[] = (string)$label;
            }
        }
        $linkParts[] = '</a>';
        return implode(null, $linkParts);
    }

    public function addBaseUrl($path)
    {
        if (!empty($this->baseUrl)) {
            if (substr($path, 0, strlen($this->baseUrl) != $this->baseUrl)) {
                return $this->baseUrl . '/' . $path;
            }
        }
        return $path;
    }

    public function isAnchorLink($link)
    {
        if (strpos($link, '#') === false) {
            return false;
        } else {
            return true;
        }
    }

    public function isRemoteLink($link)
    {
        if (strpos($link, 'http') === false) {
            return false;
        } else {
            return true;
        }
    }

    public function getIcon($icon, $alt)
    {
        $config = Zend_Registry::get('config');
        $this->iconPath = $config->filepath->icons;
        $iconPath = $this->iconPath;
        $iconPath = $this->addBaseUrl($iconPath . '/' . $icon);
        return '<img src="/' . $iconPath . '" title="' . htmlspecialchars($alt) . '" alt="' . htmlspecialchars($alt) . '" class="icon" />';
    }
}